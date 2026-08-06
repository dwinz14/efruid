@extends('layouts.app')

@section('title', 'Buat Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-2xl mx-auto">

        {{-- Stepper --}}
        @include('permohonan.partials.stepper', ['step' => 1])

        <div class="card card-body mt-6">
            <h2 class="text-base font-semibold text-slate-800 mb-1">Pilih Jenis Form</h2>
            <p class="text-sm text-slate-500 mb-6">Pilih sesuai dengan kebutuhan permohonan Anda</p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Normal --}}

                <a href="{{ route('permohonan.step2', ['form_type' => 'normal']) }}"
                class="group flex flex-col gap-3 p-5 border-2 border-slate-200 rounded-xl
                       hover:border-brand-500 hover:bg-brand-50 transition-all cursor-pointer"
                >
                <div
                    class="w-10 h-10 bg-brand-100 group-hover:bg-brand-200 rounded-lg
                            flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 group-hover:text-brand-700">Tidak Rangkap Jabatan</p>
                    <p class="text-sm text-slate-500 mt-0.5">TTD: Pemohon & Pimpinan</p>
                </div>
                <div class="flex items-center text-sm font-medium text-brand-600 mt-auto">
                    Pilih <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                </a>

                {{-- Rangkap --}}

                <a href="{{ route('permohonan.step2', ['form_type' => 'rangkap']) }}"
                class="group flex flex-col gap-3 p-5 border-2 border-slate-200 rounded-xl
                       hover:border-amber-500 hover:bg-amber-50 transition-all cursor-pointer"
                >
                <div
                    class="w-10 h-10 bg-amber-100 group-hover:bg-amber-200 rounded-lg
                            flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 group-hover:text-amber-700">Rangkap Jabatan</p>
                    <p class="text-sm text-slate-500 mt-0.5">TTD: Pemohon, Pimpinan & Direktur</p>
                </div>
                <div class="flex items-center text-sm font-medium text-amber-600 mt-auto">
                    Pilih <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                </a>

            </div>
        </div>
    </div>
@endsection
