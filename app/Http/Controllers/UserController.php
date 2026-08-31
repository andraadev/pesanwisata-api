<?php

namespace App\Http\Controllers;

use App\Http\Resources\APIResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = DB::table('users')->get();
        return new APIResource(true, "Data User", $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // try {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $user = DB::table('users')->insert($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);
        return new APIResource(true, "Selamat datang, user baru!", $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        // Jika validasi gagal (salah satu input-an tidak diisi, dsb....)
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);
        // Jika ID tidak ditemukan, maka sistem akan memberikan pesan error "ID tidak ditemukan"
        if (!$user) {
            return response()->json(["error" => 'ID tidak ditemukan.'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // Jika password diganti, dia akan meng-hash ulang, dan jika password yang diinputkan masih sama, maka ia akan menggunakan password itu.
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role
        ]);

        return new APIResource(true, "Data User Berhasil Diubah!", $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        // Jika ID tidak ditemukan, maka sistem akan memberikan pesan error "ID tidak ditemukan"
        if (!$user) {
            return response()->json(["error" => 'ID tidak ditemukan.'], 404);
        }

        $user->delete();

        return new APIResource(true, "Data User Berhasil Dihapus!", null);
    }
}
