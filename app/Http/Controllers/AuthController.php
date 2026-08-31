<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            "email" => $request->email,
            "password" => $request->password,
        ];

        $user = User::where("email", $credentials["email"])->first();

        if (Auth::attempt($credentials)) {
            $token = $user->createToken("token")->plainTextToken;
            return response()->json([
                "status" => "success",
                "status_code" => 200,
                "message" => "Login berhasil",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => "failure",
                "status_code" => 401,
                "message" => "Login gagal!"
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|min:5",
            "email" => "required|email:dns|unique:users",
            "password" => "required|min:8",
            "confirm_password" => "required|min:8|same:password"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $addUser = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => "User"
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Registrasi User Berhasil",
            "data" => $addUser
        ]);
    }
}
