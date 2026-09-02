<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') — eFRUID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-surface flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div
            class="w-20 h-20 bg-brand-100 rounded-2xl flex items-center
                    justify-center mx-auto mb-6">
            @yield('icon')
        </div>
        <h1 class="text-6xl font-bold text-brand-600 mb-3">@yield('code')</h1>
        <h2 class="text-xl font-semibold text-slate-800 mb-2">@yield('title')</h2>
        <p class="text-slate-500 text-sm mb-8">@yield('description')</p>
        <div class="flex gap-3 justify-center">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Masuk
                </a>
            @endauth
            <button onclick="history.back()" class="btn-secondary">
                Halaman Sebelumnya
            </button>
        </div>
        <p class="text-xs text-slate-400 mt-8">eFRUID — BPR Artha Pamenang</p>
    </div>
</body>

</html>
