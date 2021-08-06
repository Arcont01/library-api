<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validation = Validator::make($request -> all(), [
            'name' => ['required', 'max:191', 'string'],
            'email' => ['required', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if($validation -> fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validation -> errors() -> first(),
                'data' => $validation -> errors(),
            ], 400);
        }

        // Create user data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => [
                'user' => new UserResource($user)
            ],
        ]);

    }

}
