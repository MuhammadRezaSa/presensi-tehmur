<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    // Halaman index presensi tidak diperlukan lagi karena diintegrasikan ke dashboard kiosk,
    // namun kita biarkan atau redirect saja ke dashboard
    public function index()
    {
        return redirect()->route('karyawan.dashboard');
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'image' => 'required', // base64 dari kamera kiosk
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $today = Carbon::today();
        $now = Carbon::now();
        $cabang = Auth::user()->cabang;

        if (!$cabang) {
            return response()->json(['success' => false, 'message' => 'Sesi cabang tidak valid.']);
        }

        // 1. Dapatkan Karyawan & validasi cabang penempatan
        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        if ($karyawan->cabang_id !== $cabang->id) {
            return response()->json(['success' => false, 'message' => 'Karyawan ini tidak terdaftar di cabang Anda.']);
        }

        // 2. Cek jika sudah absen masuk hari ini
        $cek = Presensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', $today)
            ->first();
        if ($cek) {
            return response()->json(['success' => false, 'message' => 'Karyawan sudah melakukan absen masuk hari ini.']);
        }

        // 3. Validasi Jarak (Haversine Formula) dari GPS Kiosk ke Cabang
        $distance = $this->calculateDistance(
            $request->lat, $request->lng,
            $cabang->latitude, $cabang->longitude
        );

        $lokasi_valid = $distance <= $cabang->radius_meter;

        if (!$lokasi_valid) {
            return response()->json([
                'success' => false, 
                'message' => 'Posisi kiosk diluar radius cabang! Jarak kiosk: ' . round($distance) . ' meter. Radius maksimal: ' . $cabang->radius_meter . ' meter.'
            ]);
        }

        // 4. Proses upload foto (mengubah base64 menjadi file)
        $img = $request->image;
        $folderPath = "uploads/presensi/";
        $image_parts = explode(";base64,", $img);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = uniqid() . '_' . $karyawan->id . '_masuk.jpg';
        $file = public_path($folderPath) . $filename;
        
        if (!file_exists(public_path($folderPath))) {
            mkdir(public_path($folderPath), 0777, true);
        }
        
        file_put_contents($file, $image_base64);
        $fotoPath = $folderPath . $filename;

        // 5. Hitung Keterlambatan
        $jamMasukShift = Carbon::createFromTimeString($karyawan->jam_masuk_shift);
        $jamAbsen = Carbon::createFromTimeString($now->format('H:i:s'));
        
        $menitTerlambat = 0;
        $status = 'hadir';
        
        if ($jamAbsen->greaterThan($jamMasukShift)) {
            $diffInMinutes = $jamMasukShift->diffInMinutes($jamAbsen);
            if ($diffInMinutes > 10) { // Toleransi keterlambatan 10 menit
                $menitTerlambat = $diffInMinutes;
                $status = 'terlambat';
            }
        }

        // 6. Simpan ke database
        Presensi::create([
            'karyawan_id' => $karyawan->id,
            'cabang_id' => $cabang->id,
            'tanggal' => $today,
            'jam_masuk' => $now->format('H:i:s'),
            'foto_masuk' => $fotoPath,
            'lat_masuk' => $request->lat,
            'lng_masuk' => $request->lng,
            'wajah_masuk_valid' => true,
            'lokasi_masuk_valid' => true,
            'menit_terlambat' => $menitTerlambat,
            'status' => $status
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Absen masuk ' . $karyawan->nama . ' berhasil!' . ($status == 'terlambat' ? ' Terlambat ' . $menitTerlambat . ' menit.' : ' Tepat waktu.')
        ]);
    }

    public function pulang(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'image' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $today = Carbon::today();
        $now = Carbon::now();
        $cabang = Auth::user()->cabang;

        if (!$cabang) {
            return response()->json(['success' => false, 'message' => 'Sesi cabang tidak valid.']);
        }

        // 1. Dapatkan Karyawan & validasi cabang penempatan
        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        if ($karyawan->cabang_id !== $cabang->id) {
            return response()->json(['success' => false, 'message' => 'Karyawan ini tidak terdaftar di cabang Anda.']);
        }

        // 2. Cek absen masuk
        $presensi = Presensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            return response()->json(['success' => false, 'message' => 'Karyawan belum melakukan absen masuk hari ini.']);
        }

        if ($presensi->jam_pulang) {
            return response()->json(['success' => false, 'message' => 'Karyawan sudah melakukan absen pulang hari ini.']);
        }

        // 3. Validasi Jarak GPS kiosk ke Cabang
        $distance = $this->calculateDistance(
            $request->lat, $request->lng,
            $cabang->latitude, $cabang->longitude
        );

        $lokasi_valid = $distance <= $cabang->radius_meter;

        if (!$lokasi_valid) {
            return response()->json([
                'success' => false, 
                'message' => 'Posisi kiosk diluar radius cabang! Jarak kiosk: ' . round($distance) . ' meter.'
            ]);
        }

        // 4. Proses upload foto
        $img = $request->image;
        $folderPath = "uploads/presensi/";
        $image_parts = explode(";base64,", $img);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = uniqid() . '_' . $karyawan->id . '_pulang.jpg';
        $file = public_path($folderPath) . $filename;
        
        file_put_contents($file, $image_base64);
        $fotoPath = $folderPath . $filename;

        // 5. Hitung Jam Kerja & Lembur
        $jamMasuk = Carbon::createFromTimeString($presensi->jam_masuk);
        $jamPulang = Carbon::createFromTimeString($now->format('H:i:s'));
        $totalMenit = $jamMasuk->diffInMinutes($jamPulang);

        $jamPulangShift = Carbon::createFromTimeString($karyawan->jam_pulang_shift);
        $menitLembur = 0;
        
        if ($jamPulang->greaterThan($jamPulangShift)) {
            $menitLembur = $jamPulangShift->diffInMinutes($jamPulang);
        }

        // 6. Update data presensi
        $presensi->update([
            'jam_pulang' => $now->format('H:i:s'),
            'foto_pulang' => $fotoPath,
            'lat_pulang' => $request->lat,
            'lng_pulang' => $request->lng,
            'wajah_pulang_valid' => true,
            'lokasi_pulang_valid' => true,
            'total_menit_kerja' => $totalMenit,
            'menit_lembur' => $menitLembur
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Absen pulang ' . $karyawan->nama . ' berhasil!'
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // dalam meter
        
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}
