<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kantor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KantorController extends Controller
{
    public function index(): View
    {
        $kantors = Kantor::withCount('users')->orderBy('nama')->paginate(15);
        return view('admin.kantor.index', compact('kantors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:100', 'unique:kantors,nama'],
            'kode'     => ['required', 'string', 'max:10', 'alpha_num', 'unique:kantors,kode'],
            'is_pusat' => ['boolean'],
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'kode.unique'    => 'Kode ini sudah digunakan kantor lain.',
            'nama.unique'    => 'Nama kantor ini sudah ada.',
        ]);

        Kantor::create([
            'nama'      => strtoupper(trim($request->nama)),
            'kode'      => strtoupper(trim($request->kode)),
            'is_pusat'  => $request->boolean('is_pusat'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Kantor berhasil ditambahkan.');
    }

    public function update(Request $request, Kantor $kantor): RedirectResponse
    {
        $request->validate([
            'nama'      => ['required', 'string', 'max:100', 'unique:kantors,nama,' . $kantor->id],
            'kode'      => ['required', 'string', 'max:10', 'alpha_num', 'unique:kantors,kode,' . $kantor->id],
            'is_pusat'  => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $kantor->update([
            'nama'      => strtoupper(trim($request->nama)),
            'kode'      => strtoupper(trim($request->kode)),
            'is_pusat'  => $request->boolean('is_pusat'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Kantor berhasil diperbarui.');
    }

    public function destroy(Kantor $kantor): RedirectResponse
    {
        // Cegah hapus jika masih ada user atau permohonan
        if ($kantor->users()->count() > 0) {
            return back()->withErrors([
                'error' => 'Kantor tidak dapat dihapus karena masih memiliki user terdaftar.'
            ]);
        }

        $kantor->delete();

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Kantor berhasil dihapus.');
    }
}
