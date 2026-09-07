@extends('layouts.app')

@section('title', 'Preview & Submit')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-3xl mx-auto">

        @include('permohonan.partials.stepper', ['step' => 3])

        <div class="mt-6 space-y-5">

            {{-- Warning jika belum ada TTD --}}
            @if (!$user->signature_path)
                <div class="alert-danger">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586
                               10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0
                               001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586
                               8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium">Tanda tangan digital belum ada</p>
                        <p class="text-sm mt-0.5">
                            Anda harus
                            <a href="{{ route('profile.edit') }}" target="_blank" class="underline font-medium">upload tanda
                                tangan di halaman Profil</a>
                            sebelum dapat submit permohonan.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Preview dokumen --}}
            <div class="card">
                <div class="card-body p-0 overflow-x-auto">
                    {{-- Template dokumen FRUID --}}
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
                        <div class="overflow-hidden rounded-b-card" style="height: 700px;">
                            <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                                style="width:100%;height:100%;border:none;" title="Preview Dokumen FRUID"></iframe>
                        </div>
                    </div>
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
                    <button type="submit" class="btn-primary btn-lg" :disabled="loading"
                        @if (!$user->signature_path) disabled title="Upload tanda tangan terlebih dahulu" @endif>
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
