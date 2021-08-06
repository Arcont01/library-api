<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'data' => [
                'user' => new UserResource($request->user()),
            ],
        ]);

    }
}
