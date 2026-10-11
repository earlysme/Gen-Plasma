<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    /**
     * Tampilkan daftar program belajar.
     */
    public function index()
    {
        $programs = Program::withCount(['students', 'schedules'])->get();

        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Simpan program belajar baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:programs,code'],
            'is_private' => ['nullable', 'boolean'],
            'requires_room' => ['nullable', 'boolean'],
        ]);

        Program::create([
            'name' => $validated['name'],
            'code' => strtolower(trim($validated['code'])),
            'is_private' => $request->boolean('is_private'),
            'requires_room' => $request->boolean('requires_room'),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program belajar berhasil ditambahkan.');
    }

    /**
     * Perbarui data program belajar.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', Rule::unique('programs', 'code')->ignore($program->id)],
            'is_private' => ['nullable', 'boolean'],
            'requires_room' => ['nullable', 'boolean'],
        ]);

        $program->update([
            'name' => $validated['name'],
            'code' => strtolower(trim($validated['code'])),
            'is_private' => $request->boolean('is_private'),
            'requires_room' => $request->boolean('requires_room'),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program belajar berhasil diperbarui.');
    }

    /**
     * Hapus program belajar.
     */
    public function destroy(Program $program)
    {
        if ($program->students()->exists() || $program->schedules()->exists()) {
            return redirect()->route('admin.programs.index')->with('error', 'Program tidak dapat dihapus karena masih digunakan oleh jadwal atau murid.');
        }

        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program belajar berhasil dihapus.');
    }
}
