<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Hitung Statistik Ringkas
        $totalCabang = Cabang::where('is_active', true)->count();
        $totalKaryawan = Karyawan::where('is_active', true)->count();
        
        $hadirHariIni = Presensi::where('tanggal', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $terlambatHariIni = Presensi::where('tanggal', $today)
            ->where('status', 'terlambat')
            ->count();

        // 2. Ambil Aktifitas Presensi Terbaru Hari Ini
        $presensiTerbaru = Presensi::with(['karyawan', 'cabang'])
            ->where('tanggal', $today)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalCabang',
            'totalKaryawan',
            'hadirHariIni',
            'terlambatHariIni',
            'presensiTerbaru'
        ));
    }
}
