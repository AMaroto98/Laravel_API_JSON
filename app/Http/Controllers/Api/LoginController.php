<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        // Generate token
        $plainTextToken = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'plain-text-token' => $plainTextToken
        ]);
    }
}
