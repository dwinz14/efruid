<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        $query = AuditLog::with('user')->latest('created_at');

        if (! empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }
        if (! empty($this->filters['aksi'])) {
            $query->where('aksi', $this->filters['aksi']);
        }
        if (! empty($this->filters['nomor_dokumen'])) {
            $query->where('nomor_dokumen', 'like', '%' . $this->filters['nomor_dokumen'] . '%');
        }
        if (! empty($this->filters['dari'])) {
            $query->whereDate('created_at', '>=', $this->filters['dari']);
        }
        if (! empty($this->filters['sampai'])) {
            $query->whereDate('created_at', '<=', $this->filters['sampai']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Email',
            'Aksi',
            'Nomor Dokumen',
            'Model',
            'Before',
            'After',
            'IP Address',
            'Waktu',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->user?->name ?? '—',
            $log->user?->email ?? '—',
            $log->aksi instanceof \App\Enums\AksiAudit
                ? $log->aksi->label()
                : $log->aksi,
            $log->nomor_dokumen ?? '—',
            $log->subject_type  ?? '—',
            $log->before ? json_encode($log->before, JSON_UNESCAPED_UNICODE) : '—',
            $log->after  ? json_encode($log->after,  JSON_UNESCAPED_UNICODE) : '—',
            $log->ip_address ?? '—',
            $log->created_at?->format('d/m/Y H:i:s') ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'color' => ['rgb' => '1D4ED8']],
            ],
        ];
    }
}
