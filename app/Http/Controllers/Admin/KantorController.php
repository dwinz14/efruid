<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kantor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KantorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kantor::withCount('users');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kode', 'like', "%{$s}%");
            });
        }

        if ($request->filled('is_pusat')) {
            $query->where('is_pusat', $request->boolean('is_pusat'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $kantors = $query->orderByDesc('is_pusat')->orderBy('nama')->paginate(15)->withQueryString();

        $stats = [
            'total'       => Kantor::count(),
            'pusat'       => Kantor::where('is_pusat', true)->count(),
            'cabang'      => Kantor::where('is_pusat', false)->count(),
            'aktif'       => Kantor::where('is_active', true)->count(),
            'nonaktif'    => Kantor::where('is_active', false)->count(),
            'total_users' => (int) \App\Models\User::whereNotNull('kantor_id')->count(),
        ];

        return view('admin.kantor.index', compact('kantors', 'stats'));
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
