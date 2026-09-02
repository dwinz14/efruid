<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JabatanController extends Controller
{
    public function index(): View
    {
        $jabatans = Jabatan::withCount('users')->orderBy('urutan')->paginate(20);
        return view('admin.jabatan.index', compact('jabatans'));
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
