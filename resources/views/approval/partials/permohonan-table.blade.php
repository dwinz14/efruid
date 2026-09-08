@if ($pending->isEmpty())
    <div class="px-6 py-16 flex flex-col items-center justify-center text-center bg-white">
        <div
            class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-4 border border-emerald-100 shadow-sm">
            <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h4 class="text-base font-bold text-slate-800 mb-1">Semua Permohonan Selesai!</h4>
        <p class="text-sm text-slate-500 max-w-sm">
            Tidak ada permohonan yang menunggu persetujuan Anda saat ini.
        </p>
    </div>
@else
    <div class="overflow-x-auto custom-scrollbar w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                    <th class="px-5 sm:px-6 py-3.5">Dokumen & Form</th>
                    <th class="px-5 sm:px-6 py-3.5">Pemohon & Kantor</th>
                    <th class="px-5 sm:px-6 py-3.5">Jenis</th>
                    <th class="px-5 sm:px-6 py-3.5">Tanggal Pengajuan</th>
                    <th class="px-5 sm:px-6 py-3.5 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @foreach ($pending as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        {{-- Kolom Dokumen --}}
                        <td class="px-5 sm:px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span
                                    class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200/80 group-hover:border-slate-300">
                                    {{ $item->nomor_dokumen ?? 'Draft Dokumen' }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center gap-1.5">
                                @if ($item->form_type->value === 'rangkap')
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Rangkap Jabatan
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-semibold text-brand-700 px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                        Reguler (Normal)
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Kolom Pemohon --}}
                        <td class="px-5 sm:px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-brand-100 border border-brand-200 text-brand-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate">{{ $item->pemohon?->name ?? '—' }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5 truncate">
                                        {{ $item->kantor?->label ?? ($item->kantor?->nama ?? '—') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Jenis Permohonan --}}
                        <td class="px-5 sm:px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">
                                {{ $item->jenis_permohonan->label() }}
                            </span>
                        </td>

                        {{-- Kolom Tanggal Pengajuan --}}
                        <td class="px-5 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-medium text-slate-700">
                                {{ $item->tanggal_permohonan?->locale('id')->isoFormat('D MMM Y') ?? $item->updated_at->locale('id')->isoFormat('D MMM Y') }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $item->updated_at->locale('id')->isoFormat('HH:mm') }} WIB
                            </div>
                        </td>

                        {{-- Kolom Tindakan / Proses --}}
                        <td class="px-5 sm:px-6 py-4 text-right">
                            <a href="{{ $detailRoute($item) }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm shadow-brand-500/25 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:scale-95">
                                <span>Tinjau & Proses</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
