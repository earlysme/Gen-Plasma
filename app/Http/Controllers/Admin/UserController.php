<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna (filter per role).
     */
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'student');

        $query = User::with(['educationLevel', 'program', 'taughtSubjects', 'enrolledSubjects']);

        if (in_array($roleFilter, ['admin', 'tutor', 'student'])) {
            $query->where('role', $roleFilter);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('school_origin', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        $educationLevels = EducationLevel::all();
        $programs = Program::all();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roleFilter', 'educationLevels', 'programs', 'subjects'));
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,tutor,student'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'school_origin' => ['nullable', 'string', 'max:100'],
            'grade_class' => ['nullable', 'string', 'max:50'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'weekly_frequency' => ['nullable', 'integer', 'min:1', 'max:7'],
            'preferred_days' => ['nullable', 'array'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'profile_photo' => $photoPath,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'school_origin' => $validated['school_origin'] ?? null,
            'grade_class' => $validated['grade_class'] ?? null,
            'education_level_id' => $validated['education_level_id'] ?? null,
            'program_id' => $validated['program_id'] ?? null,
            'weekly_frequency' => $validated['weekly_frequency'] ?? 2,
            'preferred_days' => $validated['preferred_days'] ?? null,
        ]);

        // Tetapkan role Spatie
        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        // Simpan relasi mapel
        if (!empty($validated['subject_ids'])) {
            if ($validated['role'] === 'tutor') {
                $user->taughtSubjects()->sync($validated['subject_ids']);
            } elseif ($validated['role'] === 'student') {
                $user->enrolledSubjects()->sync($validated['subject_ids']);
            }
        }

        return redirect()->route('admin.users.index', ['role' => $validated['role']])
            ->with('success', "Akun {$validated['role']} {$user->name} berhasil ditambahkan.");
    }

    /**
     * Perbarui data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,tutor,student'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'school_origin' => ['nullable', 'string', 'max:100'],
            'grade_class' => ['nullable', 'string', 'max:50'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'weekly_frequency' => ['nullable', 'integer', 'min:1', 'max:7'],
            'preferred_days' => ['nullable', 'array'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'school_origin' => $validated['school_origin'] ?? null,
            'grade_class' => $validated['grade_class'] ?? null,
            'education_level_id' => $validated['education_level_id'] ?? null,
            'program_id' => $validated['program_id'] ?? null,
            'weekly_frequency' => $validated['weekly_frequency'] ?? 2,
            'preferred_days' => $validated['preferred_days'] ?? null,
        ];

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $updateData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Update role Spatie
        $role = Role::firstOrCreate(['name' => $validated['role'], 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        // Simpan relasi mapel
        if ($validated['role'] === 'tutor') {
            $user->taughtSubjects()->sync($validated['subject_ids'] ?? []);
        } elseif ($validated['role'] === 'student') {
            $user->enrolledSubjects()->sync($validated['subject_ids'] ?? []);
        }

        return redirect()->route('admin.users.index', ['role' => $validated['role']])
            ->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        $role = $user->role;
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        $user->roles()->detach();
        $user->taughtSubjects()->detach();
        $user->enrolledSubjects()->detach();
        $user->delete();

        return redirect()->route('admin.users.index', ['role' => $role])
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
