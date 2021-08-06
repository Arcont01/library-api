<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:191', 'exists:users'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
                'data' => $validation->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password incorrect.',
                'data' => null,
            ], 400);
        }

        $token = $user->createToken($request->userAgent())->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => [
                'token' => $token
            ],
        ]);

    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => null,
        ]);

    }
}
