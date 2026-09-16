<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#dc2626">
    <title>Dokumen Tidak Ditemukan — eFRUID</title>

    {{-- Tidak ada CDN — semua style inline, offline-ready ──────────────── --}}
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-user-select: none;
            user-select: none;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
                         'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 50%, #fdf4ff 100%);
            min-height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            -webkit-font-smoothing: antialiased;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
            padding: 40px 32px 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: icon-pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes icon-pop {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .title {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }

        .desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.65;
        }

        .desc strong {
            color: #475569;
            font-weight: 600;
        }

        .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 24px 0 18px;
        }

        .footer-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shield-mini {
            width: 24px;
            height: 24px;
            background: #f0fdf4;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-text {
            font-size: 11.5px;
            color: #059669;
            font-weight: 700;
        }

        .brand-sub {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 400px) {
            .card { padding: 32px 20px 24px; border-radius: 16px; }
            .title { font-size: 18px; }
        }
    </style>
</head>

<body>
    <div class="card">

        <div class="icon-wrap">
            <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
                 stroke="#dc2626" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9"  x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        <h1 class="title">Dokumen Tidak Ditemukan</h1>

        <p class="desc">
            Token verifikasi <strong>tidak valid</strong> atau dokumen belum dieksekusi
            melalui sistem eFRUID.<br><br>
            Pastikan QR Code yang Anda scan berasal dari dokumen
            <strong>FRUID yang asli</strong> dan sudah melalui proses eksekusi.
        </p>

        <hr class="divider">

        <div class="footer-row">
            <div class="shield-mini">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
            </div>
            <div>
                <p class="brand-text">eFRUID System</p>
                <p class="brand-sub">Sistem Verifikasi Digital &mdash; BPR</p>
            </div>
        </div>

    </div>
</body>

</html>
