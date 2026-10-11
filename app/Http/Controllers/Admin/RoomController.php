<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Tampilkan daftar ruangan.
     */
    public function index()
    {
        $rooms = Room::withCount(['schedules'])->orderBy('code')->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Simpan ruangan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:rooms,code'],
            'name' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'is_physical' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Room::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'capacity' => (int) $validated['capacity'],
            'is_physical' => $request->boolean('is_physical', true),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Perbarui data ruangan.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('rooms', 'code')->ignore($room->id)],
            'name' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'is_physical' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $room->update([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'capacity' => (int) $validated['capacity'],
            'is_physical' => $request->boolean('is_physical'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Hapus ruangan.
     */
    public function destroy(Room $room)
    {
        if ($room->schedules()->exists()) {
            return redirect()->route('admin.rooms.index')->with('error', 'Ruangan tidak dapat dihapus karena sudah memiliki riwayat jadwal. Anda dapat menonaktifkannya.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
