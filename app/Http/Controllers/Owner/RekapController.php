<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $cabang = Cabang::all();

        // Ambil filter (Default: 30 hari terakhir)
        $tglMulai = $request->get('tanggal_mulai', Carbon::today()->subDays(30)->toDateString());
        $tglSelesai = $request->get('tanggal_selesai', Carbon::today()->toDateString());
        $cabangId = $request->get('cabang_id');

        $query = Presensi::with(['karyawan', 'cabang'])
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai]);

        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }

        $presensi = $query->orderBy('tanggal', 'desc')->get();

        return view('owner.rekap.index', compact('presensi', 'cabang', 'tglMulai', 'tglSelesai', 'cabangId'));
    }

    public function exportPdf(Request $request)
    {
        $tglMulai = $request->get('tanggal_mulai', Carbon::today()->subDays(30)->toDateString());
        $tglSelesai = $request->get('tanggal_selesai', Carbon::today()->toDateString());
        $cabangId = $request->get('cabang_id');

        $query = Presensi::with(['karyawan', 'cabang'])
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai]);

        $namaCabang = "Semua Cabang";
        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
            $c = Cabang::find($cabangId);
            if ($c) {
                $namaCabang = $c->nama_cabang;
            }
        }

        $presensi = $query->orderBy('tanggal', 'asc')->get();

        // Render PDF menggunakan view khusus
        $pdf = Pdf::loadView('owner.rekap.pdf', compact('presensi', 'tglMulai', 'tglSelesai', 'namaCabang'));
        
        return $pdf->download('rekap_kehadiran_' . $tglMulai . '_ke_' . $tglSelesai . '.pdf');
    }
}
