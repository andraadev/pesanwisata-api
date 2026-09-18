<?php

namespace App\Http\Controllers;

use App\Http\Resources\APIResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /** @var \App\Models\User $user */
            $token = $user->createToken("token")->plainTextToken;

            $data = [
                'user'  => $user,
                'token' => $token,
            ];
            return new APIResource(true, "Login berhasil", $data);
        } else {
            return (new APIResource(false, "Login gagal! Email atau password salah.", null))
                ->response()
                ->setStatusCode(401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"             => "required|min:5",
            "email"            => "required|email|unique:users",
            "password"         => "required|min:8",
            "confirm_password" => "required|same:password"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            "name"     => $request->name,
            "email"    => $request->email,
            "password" => Hash::make($request->password),
            "role"     => "User"
        ]);

        $token = $user->createToken("token")->plainTextToken;

        $data = ['user' => $user, 'token' => $token,];

        return new APIResource(true, "Registrasi User Berhasil", $data);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new APIResource(true, "Berhasil keluar", null);
    }
}
