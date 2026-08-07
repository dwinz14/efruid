@if($pending->isEmpty())
    <div class="card-body text-center py-12">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                     a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-500 font-medium">Tidak ada permohonan pending</p>
        <p class="text-sm text-slate-400 mt-1">Semua permohonan sudah diproses</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="table-auto-style">
            <thead>
                <tr>
                    <th>Nomor Dokumen</th>
                    <th>Pemohon</th>
                    <th>Kantor</th>
                    <th>Jenis</th>
                    <th>Tanggal Submit</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $item)
                    <tr>
                        <td>
                            <span class="font-mono text-xs font-medium text-slate-700">
                                {{ $item->nomor_dokumen }}
                            </span>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ $item->form_type->label() }}
                            </div>
                        </td>
                        <td class="font-medium text-slate-800">
                            {{ $item->pemohon?->name }}
                        </td>
                        <td>{{ $item->kantor?->nama }}</td>
                        <td>{{ $item->jenis_permohonan->label() }}</td>
                        <td class="text-sm text-slate-500">
                            {{ $item->updated_at->locale('id')->isoFormat('D MMM Y') }}
                        </td>
                        <td class="text-right">
                            <a href="{{ $detailRoute($item) }}" class="btn-primary btn-sm">
                                Proses
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($pending->hasPages())
        <div class="px-4 py-3 border-t border-surface-border">
            {{ $pending->links() }}
        </div>
    @endif
@endif
