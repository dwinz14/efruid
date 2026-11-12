@extends('layouts.app')

@section('title', 'Periksa & Kirim Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    @php
        $isRangkap = $permohonan->form_type->value === 'rangkap';
        $step2Parameters = array_filter([
            'form_type' => $permohonan->form_type->value,
            'draft_id' => $permohonan->id,
        ], fn ($value) => filled($value));
    @endphp

    <div class="mx-auto max-w-6xl space-y-6">
        @include('permohonan.partials.stepper', ['step' => 3])

        <div class="border-b border-slate-200 pb-5 md:flex md:items-end md:justify-between md:gap-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Konfirmasi akhir</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Periksa sebelum mengirim</h2>
                <p class="mt-1.5 max-w-2xl text-sm leading-6 text-slate-600">
                    Pastikan data pada dokumen sudah tepat. Setelah dikirim, permohonan masuk ke alur persetujuan dan tidak dapat diubah.
                </p>
            </div>

            <span class="mt-4 inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold md:mt-0 {{ $isRangkap ? 'border-amber-200 bg-amber-50 text-amber-800' : 'border-brand-200 bg-brand-50 text-brand-800' }}">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ $isRangkap ? 'Rangkap Jabatan · Persetujuan Dirut' : 'Tidak Rangkap Jabatan · Persetujuan Atasan' }}
            </span>
        </div>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_18rem] xl:items-start">
            <section aria-labelledby="document-preview-heading" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50/70 px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <div>
                            <h3 id="document-preview-heading" class="text-sm font-semibold text-slate-900">Preview dokumen FRUID</h3>
                            <p class="mt-0.5 text-xs text-slate-600">Tampilan ini belum mengirim permohonan.</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Siap ditinjau
                    </span>
                </div>

                <div class="relative h-[min(72vh,52rem)] min-h-[34rem] bg-slate-100" x-data="{ loaded: false }">
                    <div x-show="!loaded" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-4 bg-slate-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-50">
                            <svg class="h-5 w-5 animate-spin text-brand-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12 12h4z" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-slate-800">Menyiapkan dokumen</p>
                            <p class="mt-1 text-xs text-slate-600">Memuat preview aman Anda.</p>
                        </div>
                    </div>
                    <iframe src="{{ route('dokumen.preview-session') }}" @load="loaded = true"
                        class="relative z-0 h-full w-full border-0 bg-white" title="Preview Dokumen FRUID"></iframe>
                </div>
            </section>

            <aside class="space-y-4 xl:sticky xl:top-4" aria-label="Ringkasan sebelum kirim">
                <x-form-section title="Sebelum mengirim">
                    <ul class="space-y-3 text-sm text-slate-700">
                        @foreach (['Periksa nama, jabatan, dan hak akses.', 'Pastikan atasan yang dipilih sudah tepat.', 'Koreksi data sebelum mengirim.'] as $check)
                            <li class="flex gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                {{ $check }}
                            </li>
                        @endforeach
                    </ul>
                </x-form-section>

                <x-alert type="warning" title="Perubahan setelah dikirim">
                    Dokumen tidak dapat diedit setelah masuk proses persetujuan.
                </x-alert>
            </aside>
        </div>

        <x-wizard-actions>
            <x-slot:back>
                <a href="{{ route('permohonan.step2', $step2Parameters) }}" class="btn btn-ghost min-h-11">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>Edit data
                </a>
            </x-slot:back>

            <span class="hidden text-xs font-medium text-slate-500 sm:block">Langkah terakhir · siap dikirim</span>

            <form method="POST" action="{{ route('permohonan.submit') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <input type="hidden" name="from_preview" value="1">
                <button type="submit" class="btn btn-primary min-h-11 px-5" :disabled="loading">
                    <x-spinner x-show="loading" x-cloak />
                    <svg x-show="!loading" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span x-text="loading ? 'Mengirim permohonan…' : 'Kirim permohonan'">Kirim permohonan</span>
                </button>
            </form>
        </x-wizard-actions>
    </div>
@endsection
