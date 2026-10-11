<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Tampilkan daftar mata pelajaran dan tutor pengampu.
     */
    public function index()
    {
        $subjects = Subject::with(['tutors'])->withCount(['schedules'])->orderBy('name')->get();
        $tutors = User::role('tutor')->orderBy('name')->get();

        return view('admin.subjects.index', compact('subjects', 'tutors'));
    }

    /**
     * Simpan mata pelajaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:100'],
            'tutor_ids' => ['nullable', 'array'],
            'tutor_ids.*' => ['exists:users,id'],
        ]);

        $subject = Subject::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
        ]);

        if (!empty($validated['tutor_ids'])) {
            $subject->tutors()->sync($validated['tutor_ids']);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Perbarui data mata pelajaran.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name' => ['required', 'string', 'max:100'],
            'tutor_ids' => ['nullable', 'array'],
            'tutor_ids.*' => ['exists:users,id'],
        ]);

        $subject->update([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
        ]);

        $subject->tutors()->sync($validated['tutor_ids'] ?? []);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus mata pelajaran.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->schedules()->exists()) {
            return redirect()->route('admin.subjects.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena masih digunakan pada jadwal bimbingan.');
        }

        $subject->tutors()->detach();
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
