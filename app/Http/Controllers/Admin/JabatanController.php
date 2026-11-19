<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JabatanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Jabatan::withCount('users');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('nama', 'like', "%{$s}%");
        }

        if ($request->filled('level')) {
            $query->where('level', (int) $request->level);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $jabatans = $query->orderBy('level')->orderBy('urutan')->orderBy('nama')->paginate(20)->withQueryString();

        $stats = [
            'total'       => Jabatan::count(),
            'direksi'     => Jabatan::whereIn('level', [Jabatan::LEVEL_DIRUT, Jabatan::LEVEL_DIREKTUR])->count(),
            'kabag'       => Jabatan::where('level', Jabatan::LEVEL_KABAG)->count(),
            'kasie'       => Jabatan::where('level', Jabatan::LEVEL_KASIE)->count(),
            'staff'       => Jabatan::where('level', Jabatan::LEVEL_STAFF)->count(),
            'aktif'       => Jabatan::where('is_active', true)->count(),
            'total_users' => (int) \App\Models\User::whereNotNull('jabatan_id')->count(),
        ];

        return view('admin.jabatan.index', compact('jabatans', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'    => ['required', 'string', 'max:100', 'unique:jabatans,nama'],
            'urutan'  => ['required', 'integer', 'min:0', 'max:999'],
            'level'   => ['required', 'integer', 'min:0', 'max:9'],
        ]);

        Jabatan::create([
            'nama'      => strtoupper(trim($request->nama)),
            'urutan'    => $request->urutan,
            'level'     => $request->level,
            'is_active' => true,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $request->validate([
            'nama'      => ['required', 'string', 'max:100', 'unique:jabatans,nama,' . $jabatan->id],
            'urutan'    => ['required', 'integer', 'min:0', 'max:999'],
            'level'     => ['required', 'integer', 'min:0', 'max:9'],
            'is_active' => ['boolean'],
        ]);

        $jabatan->update([
            'nama'      => strtoupper(trim($request->nama)),
            'urutan'    => $request->urutan,
            'level'     => $request->level,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        if ($jabatan->users()->count() > 0) {
            return back()->withErrors([
                'error' => 'Jabatan tidak dapat dihapus karena masih digunakan user.'
            ]);
        }

        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
