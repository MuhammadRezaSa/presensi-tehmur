<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Penggajian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PenggajianController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::where('is_active', true)->get();
        $penggajian = Penggajian::with('karyawan')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        return view('owner.penggajian.index', compact('karyawan', 'penggajian'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
        ]);

        $karyawan = Karyawan::find($request->karyawan_id);
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // 1. Ambil data presensi karyawan pada bulan & tahun ini
        $presensi = Presensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        // 2. Hitung statistik kehadiran
        $totalHadir = $presensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $totalTerlambat = $presensi->where('status', 'terlambat')->count();
        $totalMenitTerlambat = $presensi->sum('menit_terlambat');
        $totalMenitLembur = $presensi->sum('menit_lembur');
        
        // Asumsi hari kerja sebulan adalah 25 hari
        $hariKerjaEfektif = 25;
        $totalTidakHadir = max(0, $hariKerjaEfektif - $totalHadir);

        // 3. Kalkulasi Gaji & Potongan
        $gajiPokok = $karyawan->gaji_pokok;
        $tunjanganLembur = ($totalMenitLembur / 60) * $karyawan->upah_lembur_per_jam;
        
        // Aturan potongan:
        $potonganTerlambat = $totalMenitTerlambat * 500; // Denda Rp 500 / menit terlambat
        $potonganTidakHadir = $totalTidakHadir * ($gajiPokok / $hariKerjaEfektif); // Potong proporsional
        
        $totalGaji = max(0, ($gajiPokok + $tunjanganLembur) - ($potonganTerlambat + $potonganTidakHadir));

        // 4. Buat atau Update data slip gaji penggajian
        $penggajian = Penggajian::updateOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                'total_hari_hadir' => $totalHadir,
                'total_hari_kerja' => $hariKerjaEfektif,
                'total_menit_terlambat' => $totalMenitTerlambat,
                'total_menit_lembur' => $totalMenitLembur,
                'total_hari_tidak_hadir' => $totalTidakHadir,
                'gaji_pokok' => $gajiPokok,
                'tunjangan_lembur' => $tunjanganLembur,
                'potongan_terlambat' => $potonganTerlambat,
                'potongan_tidak_hadir' => $potonganTidakHadir,
                'total_gaji' => $totalGaji,
                'status' => 'final',
                'catatan' => 'Slip gaji digenerate otomatis oleh sistem.'
            ]
        );

        return redirect()->route('owner.penggajian.index')->with('success', 'Slip gaji berhasil digenerate!');
    }

    public function show(Penggajian $penggajian)
    {
        return view('owner.penggajian.show', compact('penggajian'));
    }

    public function exportPdf(Penggajian $penggajian)
    {
        $pdf = Pdf::loadView('owner.penggajian.pdf', compact('penggajian'));
        return $pdf->download('slip_gaji_' . strtolower(str_replace(' ', '_', $penggajian->karyawan->nama)) . '_' . $penggajian->nama_bulan . '_' . $penggajian->tahun . '.pdf');
    }
}
