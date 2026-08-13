<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AksiAudit;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AuditLogExport;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('nomor_dokumen')) {
            $query->where('nomor_dokumen', 'like', '%' . $request->nomor_dokumen . '%');
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $logs   = $query->paginate(50)->withQueryString();
        $aksis  = AksiAudit::cases();
        $users  = User::orderBy('name')->get(['id', 'name']);

        return view('admin.audit-logs.index', compact('logs', 'aksis', 'users'));
    }

    public function show(AuditLog $auditLog): View
    {
        $auditLog->load('user');
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    public function export(Request $request): mixed
    {
        $format = $request->query('format', 'xlsx');

        $filename = 'audit-log-' . now()->format('Ymd-His');

        if ($format === 'csv') {
            return Excel::download(
                new AuditLogExport($request->all()),
                $filename . '.csv',
                \Maatwebsite\Excel\Excel::CSV,
            );
        }

        return Excel::download(
            new AuditLogExport($request->all()),
            $filename . '.xlsx',
        );
    }
}
