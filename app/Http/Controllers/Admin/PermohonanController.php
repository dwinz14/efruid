<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPermohonan;
use App\Http\Controllers\Controller;
use App\Models\Kantor;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermohonanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Permohonan::with(['pemohon', 'kantor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('nomor_dokumen', 'like', "%{$s}%")
                    ->orWhere('nama_pemohon', 'like', "%{$s}%")
                    ->orWhere('nik_pemohon', 'like', "%{$s}%")
            );
        }

        $permohonan = $query->paginate(15)->withQueryString();
        $statuses   = StatusPermohonan::cases();
        $kantor    = Kantor::where('is_active', true)->orderBy('nama')->get();

        return view('admin.permohonan.index', compact('permohonan', 'statuses', 'kantor'));
    }

    public function show(Permohonan $permohonan): View
    {
        $permohonan->load('pemohon', 'kantor', 'atasan', 'approvalLogs.user');
        return view('admin.permohonan.show', compact('permohonan'));
    }
}
