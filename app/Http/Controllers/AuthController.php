<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        
        if (!Auth::attempt($validatedData)) {
            return response([
                'message' => 'La autenticación ha fallado'
            ], 401);
        }

        $user = Auth::user();
        $access_token = $user->createToken('Auth_token')->accessToken;
        
        return response([
            'user' => [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->role,
            ],
            'access_token' => $access_token
        ], 200);
    }
}
