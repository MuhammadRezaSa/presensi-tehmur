<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index()
    {
        $cabang = Auth::user()->cabang;

        if (!$cabang) {
            return redirect()->route('karyawan.dashboard');
        }

        // Ambil riwayat absen semua karyawan di cabang ini
        $riwayat = Presensi::with(['karyawan'])
            ->where('cabang_id', $cabang->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('karyawan.riwayat.index', compact('riwayat'));
    }
}
