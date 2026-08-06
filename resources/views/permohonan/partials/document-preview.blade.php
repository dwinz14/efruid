@php
    use Carbon\Carbon;

    // Format tanggal Indonesia
    $tgl = $p->tanggal_permohonan
        ? $p->tanggal_permohonan->locale('id')->isoFormat('D MMMM Y')
        : Carbon::today()->locale('id')->isoFormat('D MMMM Y');

    $kantor     = $p->kantor?->nama ?? '—';
    $isRangkap  = $p->form_type?->value === 'rangkap';
    $jenis      = $p->jenis_permohonan?->value ?? '';
    $tipePerub  = $p->tipe_perubahan?->value ?? '';

    $cbPendaftaran = $jenis === 'pendaftaran' ? '✓' : ' ';
    $cbPerubahan   = $jenis === 'perubahan'   ? '✓' : ' ';
    $cbNonaktif    = $jenis === 'nonaktif'    ? '✓' : ' ';
    $cbPermanen    = ($jenis === 'perubahan' && $tipePerub === 'permanen')  ? '✓' : ' ';
    $cbSementara   = ($jenis === 'perubahan' && $tipePerub === 'sementara') ? '✓' : ' ';

    $fmtTgl = fn($d) => $d ? \Carbon\Carbon::parse($d)->locale('id')->isoFormat('D MMM Y') : '';

    $kantorLabel = $kantor === 'PUSAT' ? 'PUSAT' : 'CABANG ' . $kantor;
    $kotaLabel   = $kantor === 'PUSAT' ? 'Pare' : ucfirst(strtolower($kantor));
@endphp

<div style="font-family: Arial, sans-serif; font-size: 11.5px; color: #000; padding: 20px 24px; max-width: 680px; margin: auto;">

    {{-- Header --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000">
        <tr>
            <td style="border:1.5px solid #000;padding:7px 10px;vertical-align:middle;font-size:11px;font-weight:bold;line-height:1.6">
                Security Administrator (IT)<br>
                Formulir Registrasi User ID (FRUID)<br>
                (Pendaftaran / Perubahan / Non Aktifkan)
            </td>
            <td style="border:1.5px solid #000;padding:7px 10px;text-align:center;width:180px;font-size:11px;vertical-align:middle">
                <strong>Tanggal Permohonan :</strong>
                <div style="font-size:12px;font-weight:bold;margin-top:3px">{{ $tgl }}</div>
            </td>
        </tr>
    </table>

    {{-- Aplikasi --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none">
        <tr><td style="text-align:center;font-weight:bold;padding:5px;font-size:12px">Aplikasi : USSI</td></tr>
    </table>

    {{-- Section bar --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none">
        <tr><td style="background:#000;color:#fff;text-align:center;font-size:11px;font-weight:bold;padding:5px">
            Diisi oleh Pemohon &ndash; Harap diisi dengan Jelas dan Benar
        </td></tr>
    </table>

    {{-- Data pemohon --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none">
        <tr><td style="padding:5px 8px;width:120px">Nama Kantor</td><td style="width:14px;text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $kantorLabel }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">Nama Lengkap</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->nama_pemohon }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">Jabatan</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->jabatan_pemohon }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">NIK</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->nik_pemohon }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">User Id</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->user_id_ussi }}</td></tr>
    </table>

    {{-- Jenis permohonan bar --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none">
        <tr><td style="background:#000;color:#fff;text-align:center;font-size:11px;font-weight:bold;padding:5px">
            Jenis Permohonan User : Beri Tanda (&radic;)
        </td></tr>
    </table>

    {{-- Isi jenis permohonan --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none">
        <tr><td style="padding:8px 10px">

            <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:5px">
                <div style="width:14px;height:14px;border:1.5px solid #000;display:inline-flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">{{ $cbPendaftaran }}</div>
                <span><strong>Pendaftaran</strong></span>
            </div>

            <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:4px">
                <div style="width:14px;height:14px;border:1.5px solid #000;display:inline-flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">{{ $cbPerubahan }}</div>
                <span><strong>Perubahan :</strong></span>
            </div>

            @if($jenis === 'perubahan' && ($p->jabatan_lama || $p->jabatan_baru))
                <div style="margin-left:20px;margin-top:2px;font-size:11px">
                    @if($p->alasan_perubahan) ({{ $p->alasan_perubahan }}) @endif
                    dari <strong>{{ $p->jabatan_lama ?? '—' }}</strong>
                    {{ $isRangkap ? 'merangkap' : 'menjadi' }}
                    <strong>{{ $p->jabatan_baru ?? '—' }}</strong>
                </div>
            @endif

            <div style="margin-left:20px">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;font-size:11px">
                    <div style="width:14px;height:14px;border:1.5px solid #000;display:inline-flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">{{ $cbPermanen }}</div>
                    <span style="width:80px"><strong>Permanen</strong></span>
                    <span>Mulai Tgl. :</span>
                    <span style="min-width:80px;border-bottom:1px solid #000;padding:0 4px">
                        {{ $jenis === 'perubahan' && $tipePerub === 'permanen' ? $fmtTgl($p->tgl_permanen) : '' }}
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;font-size:11px">
                    <div style="width:14px;height:14px;border:1.5px solid #000;display:inline-flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">{{ $cbSementara }}</div>
                    <span style="width:80px"><strong>Sementara</strong></span>
                    <span>Mulai Tgl. :</span>
                    <span style="min-width:80px;border-bottom:1px solid #000;padding:0 4px">
                        {{ $jenis === 'perubahan' && $tipePerub === 'sementara' ? $fmtTgl($p->tgl_mulai) : '' }}
                    </span>
                    <span>s/d</span>
                    <span style="min-width:80px;border-bottom:1px solid #000;padding:0 4px">
                        {{ $jenis === 'perubahan' && $tipePerub === 'sementara' ? $fmtTgl($p->tgl_selesai) : '' }}
                    </span>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-top:5px;font-size:11px">
                <div style="width:14px;height:14px;border:1.5px solid #000;display:inline-flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">{{ $cbNonaktif }}</div>
                <span><strong>Non Aktifkan</strong></span>
                <span style="margin-left:8px">Mulai Tgl. :</span>
                <span style="min-width:100px;border-bottom:1px solid #000;padding:0 4px">
                    {{ $jenis === 'nonaktif' ? $fmtTgl($p->tgl_nonaktif) : '' }}
                </span>
            </div>

        </td></tr>
    </table>

    {{-- TTD table --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none;table-layout:fixed">
        <tr>
            <td style="border:1.5px solid #000;text-align:center;font-size:11.5px;padding:6px;font-weight:bold">Pemohon</td>
            <td style="border:1.5px solid #000;text-align:center;font-size:11.5px;padding:6px;font-weight:bold">Pimpinan</td>
            @if($isRangkap)
                <td style="border:1.5px solid #000;text-align:center;font-size:11.5px;padding:6px;font-weight:bold">Direksi</td>
            @endif
        </tr>
        <tr>
            <td style="height:80px;border:1.5px solid #000"></td>
            <td style="height:80px;border:1.5px solid #000"></td>
            @if($isRangkap)
                <td style="height:80px;border:1.5px solid #000"></td>
            @endif
        </tr>
        <tr>
            <td style="border:1.5px solid #000;text-align:center;font-size:11px;padding:4px 6px">{{ $p->nama_pemohon }}</td>
            <td style="border:1.5px solid #000;text-align:center;font-size:11px;padding:4px 6px">{{ $p->nama_atasan_ttd ?? '( ________________ )' }}</td>
            @if($isRangkap)
                <td style="border:1.5px solid #000;text-align:center;font-size:11px;padding:4px 6px">FRANSISKA HENDRA</td>
            @endif
        </tr>
    </table>

    {{-- Section: Diisi IT --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none;margin-top:8px">
        <tr><td style="background:#000;color:#fff;text-align:center;font-size:11px;font-weight:bold;padding:5px">
            Diisi oleh Administrator Aplikasi USSI
        </td></tr>
    </table>

    <table style="width:100%;border-collapse:collapse;border:1.5px solid #000;border-top:none;margin-bottom:10px">
        <tr><td style="padding:5px 8px;width:120px">User Id</td><td style="width:14px;text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->user_id_ussi }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">Jabatan</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->jabatan_pemohon }}</td></tr>
        <tr style="border-top:1px solid #ccc"><td style="padding:5px 8px">Access Level</td><td style="text-align:center">:</td><td style="font-weight:bold;padding:5px 8px">{{ $p->access_level?->value }}</td></tr>
    </table>

    <p style="text-align:right;font-size:11.5px;margin-bottom:6px">{{ $kotaLabel }}&nbsp;,&nbsp;{{ $tgl }}</p>

    <div style="width:200px;border:1.5px solid #000;text-align:center">
        <div style="background:#000;color:#fff;font-size:10.5px;font-weight:bold;padding:5px">Administrator Aplikasi USSI</div>
        <div style="height:70px"></div>
        <div style="border-top:1px solid #000;font-size:10.5px;font-weight:bold;padding:4px">AGUS NAWAWI, ST</div>
    </div>

</div>
