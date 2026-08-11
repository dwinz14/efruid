<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>FRUID &mdash; {{ $p->nomor_dokumen ?? 'Draft' }}</title>
    <style>
        /*
 * FRUID Document Stylesheet
 * Kompatibel: Browser modern + dompdf (CSS2 subset)
 * Layout: HTML table only — tidak ada flex/grid/position:absolute
 */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            color: #000;
            background: #fff;
        }

        /* ── Root container ── */
        .doc-page {
            width: 740px;
            margin: 0 auto;
            padding: 28px 30px;
        }

        /* ── Tables ── */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
        }

        .doc-table-bordered {
            border: 1.5px solid #000;
        }

        .doc-table-bt-none {
            border-top: none;
        }

        /* ── Header ── */
        .doc-header-left {
            padding: 7px 10px;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.7;
            vertical-align: middle;
            border: 1.5px solid #000;
        }

        .doc-header-right {
            padding: 7px 10px;
            text-align: center;
            width: 190px;
            font-size: 11px;
            vertical-align: middle;
            border: 1.5px solid #000;
        }

        .doc-header-date {
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ── Section bar ── */
        .doc-bar td {
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            padding: 5px 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Data rows ── */
        .doc-data td {
            padding: 5px 8px;
            font-size: 11.5px;
            vertical-align: top;
        }

        .doc-data .col-label {
            width: 120px;
        }

        .doc-data .col-sep {
            width: 14px;
            text-align: center;
        }

        .doc-data .col-value {
            font-weight: bold;
        }

        .doc-data .row-border td {
            border-top: 1px solid #ccc;
        }

        /* ── Jenis permohonan ── */
        .perm-section {
            padding: 5px 8px 7px 8px;
            font-size: 11px;
            line-height: 1.25;
        }

        /* Semua baris utama */
        .perm-line {
            display: flex;
            align-items: center;
            min-height: 20px;
            white-space: nowrap;
        }

        /* Checkbox */
        .perm-checkbox {
            width: 20px;
            min-width: 20px;
            display: inline-flex;
            align-items: center;
        }

        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            line-height: 10px;
            text-align: center;
            font-size: 9px;
            vertical-align: middle;
            box-sizing: border-box;
            margin-right: 5px;
        }

        /* Checkbox sub bagian dibuat lebih masuk ke kanan */
        .perm-sub {
            margin-left: 20px;
        }

        /* Keterangan:
   (alasan) dari STAFF IT menjadi ACCOUNTING STAFF
*/
        .perm-alasan {
            margin-left: 4px;
        }

        .perm-change-desc {
            margin-left: 84px;
            white-space: nowrap;
        }

        /* Label Permanen / Sementara */
        .perm-label {
            width: 90px;
            min-width: 90px;
        }

        /* "Mulai Tgl. :" */
        .perm-date-label {
            margin-left: 0;
            width: 70px;
            min-width: 70px;
        }

        /* Garis tanggal */
        .perm-dateline {
            display: inline-block;
            width: 108px;
            min-width: 108px;
            height: 16px;
            line-height: 16px;
            border-bottom: 1px solid #555;
            text-align: left;
            padding-left: 4px;
        }

        /* s/d */
        .perm-sd {
            margin: 0 5px;
        }

        /* Garis tanggal selesai */
        .perm-date-end {
            width: 108px;
            min-width: 108px;
        }

        /* Non aktif agak diberi jarak dari Sementara */
        .perm-nonaktif {
            margin-top: 5px;
        }


        /* ── TTD table ── */
        .doc-ttd td {
            border: 1.5px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 6px 4px;
        }

        .doc-ttd .ttd-header {
            font-size: 11.5px;
            font-weight: bold;
            padding: 6px;
        }

        .doc-ttd .ttd-body {
            height: 90px;
            vertical-align: middle;
        }

        .doc-ttd .ttd-name {
            font-size: 11px;
            font-weight: normal;
            border-top: 1px solid #000;
            padding: 4px 6px;
        }

        .ttd-img {
            max-height: 60px;
            max-width: 140px;
            display: block;
            margin: 0 auto 3px auto;
        }

        .ttd-stamp {
            font-size: 7.5px;
            color: #444;
            line-height: 1.4;
            margin-top: 2px;
        }

        .ttd-stamp-hash {
            font-family: monospace;
            font-size: 7px;
            color: #888;
            word-break: break-all;
        }

        /* ── Admin TTD box ── */
        .doc-admin-box {
            width: 200px;
            border: 1.5px solid #000;
            text-align: center;
        }

        .doc-admin-bar {
            background: #000;
            color: #fff;
            font-size: 10.5px;
            font-weight: bold;
            padding: 5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .doc-admin-space {
            height: 70px;
        }

        .doc-admin-name {
            border-top: 1px solid #000;
            font-size: 10.5px;
            font-weight: bold;
            padding: 4px;
        }

        /* ── Verification record ── */
        .doc-vr-section {
            margin-top: 14px;
            border-top: 2px dashed #bbb;
            padding-top: 10px;
        }

        .doc-vr-title {
            font-size: 9px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .doc-vr-box {
            border: 1px solid #bbb;
            padding: 5px 8px;
            margin-bottom: 5px;
            font-size: 9px;
            color: #333;
            line-height: 1.5;
        }

        .doc-vr-hash {
            font-family: monospace;
            font-size: 8px;
            color: #777;
            word-break: break-all;
        }

        /* ── Print media ── */
        @media print {
            body {
                margin: 0;
            }

            .doc-page {
                padding: 20px 24px;
            }

            @page {
                margin: 15mm 12mm 15mm 12mm;
                size: A4 portrait;
            }
        }
    </style>
</head>

<body>
    <div class="doc-page">

        {{-- ── HEADER ── --}}
        <table class="doc-table doc-table-bordered">
            <tr>
                <td class="doc-header-left">
                    Security Administrator (IT)<br>
                    Formulir Registrasi User ID (FRUID)<br>
                    (Pendaftaran / Perubahan / Non Aktifkan)
                </td>
                <td class="doc-header-right">
                    <strong>Tanggal Permohonan :</strong>
                    <div class="doc-header-date">{{ $tgl }}</div>
                </td>
            </tr>
        </table>

        {{-- Aplikasi --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none">
            <tr>
                <td style="text-align:center;font-weight:bold;padding:5px;font-size:12px">
                    Aplikasi : USSI
                </td>
            </tr>
        </table>

        {{-- Section bar: Pemohon --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-bar">
            <tr>
                <td>Diisi oleh Pemohon &ndash; Harap diisi dengan Jelas dan Benar</td>
            </tr>
        </table>

        {{-- Data pemohon --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-data">
            <tr>
                <td class="col-label">Nama Kantor</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $kantorLabel }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">Nama Lengkap</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->nama_pemohon }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">Jabatan</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->jabatan_pemohon }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">NIK</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->nik_pemohon }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">User Id</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->user_id_ussi }}</td>
            </tr>
        </table>

        {{-- Section bar: Jenis --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-bar">
            <tr>
                <td>Jenis Permohonan User : Beri Tanda (&radic;)</td>
            </tr>
        </table>

        {{-- Isi jenis permohonan --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none">
            <tr>
                <td class="perm-section">

                    {{-- Pendaftaran --}}
                    <div class="perm-line">
                        <span class="checkbox-box">{!! $cbPendaftaran !!}</span>
                        <strong>Pendaftaran</strong>
                    </div>

                    {{-- Perubahan --}}
                    <div class="perm-line">
                        <span class="checkbox-box">{!! $cbPerubahan !!}</span>

                        <strong>Perubahan :</strong>

                        @if ($jenis === 'perubahan' && ($p->jabatan_lama || $p->jabatan_baru))
                            <span class="perm-alasan">
                                @if ($p->alasan_perubahan)
                                    ({{ $p->alasan_perubahan }})
                                @endif
                            </span>
                        @endif
                    </div>

                    {{-- Keterangan perubahan --}}
                    @if ($jenis === 'perubahan' && ($p->jabatan_lama || $p->jabatan_baru))
                        <div class="perm-change-desc">
                            dari <strong>{{ $p->jabatan_lama ?? '—' }}</strong>
                            {{ $isRangkap ? 'merangkap' : 'menjadi' }}
                            <strong>{{ $p->jabatan_baru ?? '—' }}</strong>
                        </div>
                    @endif

                    {{-- Permanen --}}
                    <div class="perm-line perm-sub">
                        <span class="checkbox-box">{!! $cbPermanen !!}</span>

                        <strong class="perm-label">Permanen</strong>

                        <span class="perm-date-label">Mulai Tgl. :</span>

                        <span class="perm-dateline">
                            {{ $jenis === 'perubahan' && $tipePerub === 'permanen' ? $tglPermanen : '' }}
                        </span>
                    </div>

                    {{-- Sementara --}}
                    <div class="perm-line perm-sub">
                        <span class="checkbox-box">{!! $cbSementara !!}</span>

                        <strong class="perm-label">Sementara</strong>

                        <span class="perm-date-label">Mulai Tgl. :</span>

                        <span class="perm-dateline">
                            {{ $jenis === 'perubahan' && $tipePerub === 'sementara' ? $tglMulai : '' }}
                        </span>

                        <span class="perm-sd">s/d</span>

                        <span class="perm-dateline perm-date-end">
                            {{ $jenis === 'perubahan' && $tipePerub === 'sementara' ? $tglSelesai : '' }}
                        </span>
                    </div>

                    {{-- Non Aktifkan --}}
                    <div class="perm-line perm-nonaktif">
                        <span class="checkbox-box">{!! $cbNonaktif !!}</span>

                        <strong class="perm-label">Non Aktifkan</strong>

                        <span class="perm-date-label">Mulai Tgl. :</span>

                        <span class="perm-dateline">
                            {{ $jenis === 'nonaktif' ? $tglNonaktif : '' }}
                        </span>
                    </div>

                </td>
            </tr>
        </table>


        {{-- ── TTD TABLE ── --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-ttd" style="table-layout:fixed">
            {{-- Header --}}
            <tr>
                <td class="ttd-header">Pemohon</td>
                <td class="ttd-header">Pimpinan</td>
                @if ($isRangkap)
                    <td class="ttd-header">Direksi</td>
                @endif
            </tr>

            {{-- Gambar TTD + Stamp --}}
            <tr>
                {{-- Pemohon --}}
                <td class="ttd-body">
                    @if ($ttdPemohon)
                        <img src="{{ $ttdPemohon }}" class="ttd-img" alt="TTD Pemohon">
                    @endif
                </td>

                {{-- Atasan/Pimpinan --}}
                <td class="ttd-body">
                    @if ($ttdAtasan)
                        <img src="{{ $ttdAtasan }}" class="ttd-img" alt="TTD Atasan">
                    @endif
                    @if ($stampAtasan)
                        <div class="ttd-stamp">
                            {{ $stampAtasan['timestamp'] }}<br>
                            <span class="ttd-stamp-hash">
                                {{ substr($stampAtasan['hash'], 0, 24) }}...
                            </span>
                        </div>
                    @endif
                </td>

                {{-- Direksi (rangkap) --}}
                @if ($isRangkap)
                    <td class="ttd-body">
                        @if ($ttdDirut)
                            <img src="{{ $ttdDirut }}" class="ttd-img" alt="TTD Direktur">
                        @endif
                        @if ($stampDirut)
                            <div class="ttd-stamp">
                                {{ $stampDirut['timestamp'] }}<br>
                                <span class="ttd-stamp-hash">
                                    {{ substr($stampDirut['hash'], 0, 24) }}...
                                </span>
                            </div>
                        @endif
                    </td>
                @endif
            </tr>

            {{-- Nama --}}
            <tr>
                <td class="ttd-name">{{ $p->nama_pemohon }}</td>
                <td class="ttd-name">
                    {{ $p->nama_atasan_ttd ?? '( ________________ )' }}
                </td>
                @if ($isRangkap)
                    <td class="ttd-name">FRANSISKA HENDRA</td>
                @endif
            </tr>
        </table>

        {{-- Section bar: IT --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-bar" style="margin-top:8px">
            <tr>
                <td>Diisi oleh Administrator Aplikasi USSI</td>
            </tr>
        </table>

        {{-- Data IT --}}
        <table class="doc-table doc-table-bordered doc-table-bt-none doc-data" style="margin-bottom:10px">
            <tr>
                <td class="col-label">User Id</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->user_id_ussi }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">Jabatan</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->jabatan_pemohon }}</td>
            </tr>
            <tr class="row-border">
                <td class="col-label">Access Level</td>
                <td class="col-sep">:</td>
                <td class="col-value">{{ $p->access_level?->value }}</td>
            </tr>
        </table>

        {{-- Kota, tanggal, TTD admin --}}
        <table class="doc-table">
            <tr>
                {{-- TANGGAL DI KANAN, TETAP DI ATAS --}}
                <td style="width:50%; text-align:right; vertical-align:top;">
                    <p style="font-size:11.5px; margin:0 0 6px 0;">
                        {{ $kotaLabel }}&nbsp;,&nbsp;{{ $tgl }}
                    </p>
                </td>
            </tr>
            <tr>
                {{-- ADMIN BOX DI KIRI --}}
                <td style="width:50%; vertical-align:bottom;">
                    <div class="doc-admin-box">
                        <div class="doc-admin-bar">Administrator Aplikasi USSI</div>
                        <div class="doc-admin-space"></div>
                        <div class="doc-admin-name">AGUS SETIAWAN</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── VERIFICATION RECORD ── --}}
        @if (count($stamps) > 0)
            <div class="doc-vr-section">
                <div class="doc-vr-title">Verification Record &mdash; eFRUID System</div>
                @foreach ($stamps as $stamp)
                    <div class="doc-vr-box">
                        <strong>{{ $stamp['role'] }}</strong>:
                        {{ $stamp['nama'] }}
                        ({{ $stamp['jabatan'] }})
                        <br>
                        Disetujui: {{ $stamp['timestamp'] }}<br>
                        <span class="doc-vr-hash">SHA256: {{ $stamp['hash'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</body>

</html>
