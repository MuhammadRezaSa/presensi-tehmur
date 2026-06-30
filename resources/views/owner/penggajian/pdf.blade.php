<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $penggajian->karyawan->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .container {
            border: 1px solid #ccc;
            padding: 30px;
            border-radius: 5px;
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 10px;
            color: #555;
        }

        .row {
            clear: both;
            padding: 4px 0;
        }

        .label {
            float: left;
            width: 60%;
        }

        .val {
            float: right;
            width: 40%;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #333;
            padding-top: 8px;
            margin-top: 15px;
        }

        .footer-sign {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>SLIP GAJI KARYAWAN</h2>
            <p>Outlet Teh Mur — {{ $penggajian->nama_bulan }} {{ $penggajian->tahun }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <div style="float: left; width: 50%;">
                <strong>Nama Karyawan:</strong> {{ $penggajian->karyawan->name }}<br>
                <strong>Jabatan:</strong> {{ $penggajian->karyawan->jabatan }}<br>
                <strong>NIK:</strong> {{ $penggajian->karyawan->nik ?? '-' }}
            </div>
            <div style="float: right; width: 50%; text-align: right;">
                <strong>Outlet Penugasan:</strong> {{ $penggajian->karyawan->cabang->nama_cabang }}<br>
                <strong>Hari Hadir:</strong> {{ $penggajian->total_hari_hadir }} Hari<br>
                <strong>Mangkir/Bolos:</strong> {{ $penggajian->total_hari_tidak_hadir }} Hari
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="section-title">Penerimaan & Lembur</div>
        <div class="row">
            <div class="label">Gaji Pokok</div>
            <div class="val">Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</div>
        </div>
        <div class="row">
            <div class="label">Tunjangan Lembur ({{ intdiv($penggajian->total_menit_lembur, 60) }}j {{ $penggajian->total_menit_lembur % 60 }}m)</div>
            <div class="val">Rp {{ number_format($penggajian->tunjangan_lembur, 0, ',', '.') }}</div>
        </div>

        <div class="section-title">Potongan Kedisiplinan</div>
        <div class="row">
            <div class="label">Potongan Ketidakhadiran ({{ $penggajian->total_hari_tidak_hadir }} Hari)</div>
            <div class="val">Rp {{ number_format($penggajian->potongan_tidak_hadir, 0, ',', '.') }}</div>
        </div>
        <div class="row">
            <div class="label">Potongan Keterlambatan ({{ $penggajian->total_menit_terlambat }} Menit)</div>
            <div class="val">Rp {{ number_format($penggajian->potongan_terlambat, 0, ',', '.') }}</div>
        </div>

        <div class="total-row">
            <div class="label">Gaji Bersih Diterima (Take Home Pay)</div>
            <div class="val">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</div>
        </div>

        <div class="footer-sign">
            <p>Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 60px;">Muhammad Reza Sajidin<br><strong>(Owner Teh Mur)</strong></p>
        </div>
    </div>

</body>
</html>
