<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = Cabang::with('user')->get();
        return view('owner.cabang.index', compact('cabang'));
    }

    public function create()
    {
        return view('owner.cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|numeric|min:10',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'pin_tambah_karyawan' => 'required|string|size:6|regex:/^\d{6}$/',
        ], [
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'latitude.required' => 'Latitude wajib diisi.',
            'longitude.required' => 'Longitude wajib diisi.',
            'radius_meter.required' => 'Radius validasi wajib diisi.',
            'email.required' => 'Email login cabang wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Password login cabang wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'pin_tambah_karyawan.required' => 'PIN Kiosk wajib diisi.',
            'pin_tambah_karyawan.size' => 'PIN Kiosk harus berupa 6 digit angka.',
            'pin_tambah_karyawan.regex' => 'PIN Kiosk harus berupa angka saja.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User Cabang
            $user = User::create([
                'name' => 'Admin ' . $request->nama_cabang,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'cabang',
                'is_active' => true,
            ]);

            // 2. Buat Cabang
            Cabang::create([
                'user_id' => $user->id,
                'nama_cabang' => $request->nama_cabang,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius_meter' => $request->radius_meter,
                'pin_tambah_karyawan' => $request->pin_tambah_karyawan,
                'is_active' => true,
            ]);
        });

        return redirect()->route('owner.cabang.index')->with('success', 'Cabang baru berhasil ditambahkan!');
    }

    public function edit(Cabang $cabang)
    {
        return view('owner.cabang.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|numeric|min:10',
            'email' => 'required|string|email|max:255|unique:users,email,' . $cabang->user_id,
            'password' => 'nullable|string|min:6',
            'pin_tambah_karyawan' => 'required|string|size:6|regex:/^\d{6}$/',
        ], [
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'latitude.required' => 'Latitude wajib diisi.',
            'longitude.required' => 'Longitude wajib diisi.',
            'radius_meter.required' => 'Radius validasi wajib diisi.',
            'email.required' => 'Email login cabang wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal 6 karakter.',
            'pin_tambah_karyawan.required' => 'PIN Kiosk wajib diisi.',
            'pin_tambah_karyawan.size' => 'PIN Kiosk harus berupa 6 digit angka.',
            'pin_tambah_karyawan.regex' => 'PIN Kiosk harus berupa angka saja.',
        ]);

        DB::transaction(function () use ($request, $cabang) {
            // 1. Update User Cabang
            $user = $cabang->user;
            if ($user) {
                $user->name = 'Admin ' . $request->nama_cabang;
                $user->email = $request->email;
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            }

            // 2. Update Cabang
            $cabang->update([
                'nama_cabang' => $request->nama_cabang,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius_meter' => $request->radius_meter,
                'pin_tambah_karyawan' => $request->pin_tambah_karyawan,
            ]);
        });

        return redirect()->route('owner.cabang.index')->with('success', 'Data cabang berhasil diperbarui!');
    }

    public function destroy(Cabang $cabang)
    {
        DB::transaction(function () use ($cabang) {
            $user = $cabang->user;
            $cabang->delete();
            if ($user) {
                $user->delete();
            }
        });
        return redirect()->route('owner.cabang.index')->with('success', 'Cabang berhasil dihapus!');
    }
}
