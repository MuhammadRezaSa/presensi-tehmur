<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $cabang = Auth::user()->cabang;

        if (!$cabang) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun cabang ini tidak terhubung dengan data Cabang manapun.']);
        }

        // Ambil semua karyawan yang bekerja di cabang ini
        $karyawanList = Karyawan::where('cabang_id', $cabang->id)
            ->where('is_active', true)
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil semua presensi hari ini di cabang ini
        $presensiToday = Presensi::where('cabang_id', $cabang->id)
            ->where('tanggal', $today)
            ->get()
            ->keyBy('karyawan_id');

        return view('karyawan.dashboard', compact('cabang', 'karyawanList', 'presensiToday'));
    }

    public function tambahKaryawan(Request $request)
    {
        $cabang = Auth::user()->cabang;

        if (!$cabang) {
            return response()->json(['success' => false, 'message' => 'Cabang tidak terdeteksi.']);
        }

        // Validasi PIN Kiosk
        if ($request->pin !== $cabang->pin_tambah_karyawan) {
            return response()->json(['success' => false, 'message' => 'PIN Kiosk salah! Silakan hubungi Owner untuk mengetahui PIN.']);
        }

        // Validasi data input karyawan
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nik' => 'nullable|string|unique:karyawan,nik',
            'no_telepon' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'shift' => 'required|in:pagi,siang,malam',
            'jam_masuk_shift' => 'required',
            'jam_pulang_shift' => 'required',
            'gaji_pokok' => 'required|numeric|min:0',
            'upah_lembur_per_jam' => 'required|numeric|min:0',
            'foto_wajah' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Foto wajah wajib untuk face recognition
        ]);

        // Upload Foto Wajah
        $fotoPath = 'profiles/default.jpg';
        if ($request->hasFile('foto_wajah')) {
            $file = $request->file('foto_wajah');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $fotoPath = 'uploads/profiles/' . $filename;
        }

        // Buat Karyawan
        Karyawan::create([
            'cabang_id' => $cabang->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_telepon' => $request->no_telepon,
            'foto_wajah' => $fotoPath,
            'jabatan' => $request->jabatan,
            'shift' => $request->shift,
            'jam_masuk_shift' => $request->jam_masuk_shift,
            'jam_pulang_shift' => $request->jam_pulang_shift,
            'gaji_pokok' => $request->gaji_pokok,
            'upah_lembur_per_jam' => $request->upah_lembur_per_jam,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Karyawan baru berhasil ditambahkan!']);
    }
}
