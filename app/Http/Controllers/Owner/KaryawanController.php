<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::with(['cabang'])->get();
        return view('owner.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $cabangs = Cabang::where('is_active', true)->get();
        return view('owner.karyawan.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cabang_id' => 'required|exists:cabang,id',
            'nik' => 'nullable|string|unique:karyawan,nik',
            'no_telepon' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'shift' => 'required|in:pagi,siang,malam',
            'jam_masuk_shift' => 'required',
            'jam_pulang_shift' => 'required',
            'gaji_pokok' => 'required|numeric|min:0',
            'upah_lembur_per_jam' => 'required|numeric|min:0',
            'foto_wajah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'cabang_id.required' => 'Cabang wajib dipilih.',
            'gaji_pokok.required' => 'Gaji pokok wajib diisi.',
            'upah_lembur_per_jam.required' => 'Upah lembur wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            // Upload Foto Wajah jika ada
            $fotoPath = 'profiles/default.jpg';
            if ($request->hasFile('foto_wajah')) {
                $file = $request->file('foto_wajah');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profiles'), $filename);
                $fotoPath = 'uploads/profiles/' . $filename;
            }

            // Buat Profile Karyawan
            Karyawan::create([
                'cabang_id' => $request->cabang_id,
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
        });

        return redirect()->route('owner.karyawan.index')->with('success', 'Karyawan baru berhasil didaftarkan!');
    }

    public function edit(Karyawan $karyawan)
    {
        $cabangs = Cabang::where('is_active', true)->get();
        return view('owner.karyawan.edit', compact('karyawan', 'cabangs'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cabang_id' => 'required|exists:cabang,id',
            'nik' => 'nullable|string|unique:karyawan,nik,' . $karyawan->id,
            'no_telepon' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'shift' => 'required|in:pagi,siang,malam',
            'jam_masuk_shift' => 'required',
            'jam_pulang_shift' => 'required',
            'gaji_pokok' => 'required|numeric|min:0',
            'upah_lembur_per_jam' => 'required|numeric|min:0',
            'foto_wajah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            // Update Karyawan Profile
            $karyawanData = $request->only([
                'nama', 'email', 'cabang_id', 'nik', 'no_telepon', 'jabatan', 'shift', 
                'jam_masuk_shift', 'jam_pulang_shift', 'gaji_pokok', 'upah_lembur_per_jam'
            ]);

            // Handle Foto Baru
            if ($request->hasFile('foto_wajah')) {
                // Hapus foto lama jika bukan default
                if ($karyawan->foto_wajah && $karyawan->foto_wajah != 'profiles/default.jpg' && file_exists(public_path($karyawan->foto_wajah))) {
                    @unlink(public_path($karyawan->foto_wajah));
                }

                $file = $request->file('foto_wajah');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profiles'), $filename);
                $karyawanData['foto_wajah'] = 'uploads/profiles/' . $filename;
            }

            $karyawan->update($karyawanData);
        });

        return redirect()->route('owner.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Karyawan $karyawan)
    {
        DB::transaction(function () use ($karyawan) {
            // Hapus foto wajah
            if ($karyawan->foto_wajah && $karyawan->foto_wajah != 'profiles/default.jpg' && file_exists(public_path($karyawan->foto_wajah))) {
                @unlink(public_path($karyawan->foto_wajah));
            }
            
            // Hapus Profile Karyawan (Cascade data presensi)
            $karyawan->delete();
        });

        return redirect()->route('owner.karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}
