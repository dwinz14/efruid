@extends('layouts.app')

@section('title', 'Preview & Submit')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-3xl mx-auto">

        @include('permohonan.partials.stepper', ['step' => 3])

        <div class="mt-6 space-y-5">

            {{-- Warning jika belum ada TTD --}}
            @if (!$user->signature_path)
                <div class="alert-warning">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium">Tanda tangan digital belum ada</p>
                        <p class="text-sm mt-0.5">
                            Anda dapat tetap submit, namun disarankan untuk
                            <a href="{{ route('profile.edit') }}#signature" target="_blank"
                                class="underline font-medium">upload tanda tangan</a> terlebih dahulu.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Preview dokumen --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Preview Dokumen</h2>
                    <div class="flex gap-2">
                        @if ($permohonan->form_type->value === 'rangkap')
                            <span class="badge bg-amber-100 text-amber-700">Rangkap Jabatan</span>
                        @else
                            <span class="badge bg-brand-100 text-brand-700">Tidak Rangkap Jabatan</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0 overflow-x-auto">
                    {{-- Template dokumen FRUID --}}
                    @include('permohonan.partials.document-preview', ['p' => $permohonan])
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex items-center justify-between pb-4">
                <a href="{{ route('permohonan.step2', [
                    'form_type' => $permohonan->form_type->value,
                    'draft_id' => $permohonan->id,
                ]) }}"
                    class="btn-ghost">
                    ← Edit Data
                </a>

                <form method="POST" action="{{ route('permohonan.submit') }}" x-data="{ loading: false }"
                    @submit="loading = true">
                    @csrf
                    <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">
                    <button type="submit" class="btn-primary btn-lg" :disabled="loading">
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span x-text="loading ? 'Mengirim...' : 'Submit Permohonan'">Submit Permohonan</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
