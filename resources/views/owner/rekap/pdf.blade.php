<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Kehadiran Teh Mur</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #002855;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .status-hadir { color: #15803d; }
        .status-terlambat { color: #b45309; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Rekap Kehadiran Karyawan</h2>
        <p>Outlet Teh Mur Pekanbaru</p>
    </div>

    <div class="info">
        <strong>Cabang:</strong> {{ $namaCabang }}<br>
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d F Y') }}<br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Cabang</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Lembur (Menit)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($presensi as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                    <td><strong>{{ $p->karyawan->name }}</strong><br><span style="font-size: 9px; color: #666;">{{ $p->karyawan->jabatan }}</span></td>
                    <td>{{ $p->cabang->nama_cabang }}</td>
                    <td>{{ substr($p->jam_masuk, 0, 5) }}</td>
                    <td>{{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}</td>
                    <td>{{ $p->menit_lembur }}</td>
                    <td>
                        <span class="status status-{{ $p->status }}">
                            {{ $p->status == 'hadir' ? 'Tepat Waktu' : 'Terlambat (' . $p->menit_terlambat . 'm)' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data presensi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
