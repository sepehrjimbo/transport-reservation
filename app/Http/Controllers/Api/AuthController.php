<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'error'=>$validator->errors()], 401);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);
        $token = auth('api')->login($user);
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password,
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => auth('api')->user(),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ],
        ]);
    }
}
