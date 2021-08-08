<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:191', 'exists:users'],
            'password' => ['required'],
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => null,
        ]);

    }
}
