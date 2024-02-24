<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;


class LoginController extends Controller
{
    public function __invoke(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')]
            ]);
        }

        // Generate token
        $plainTextToken = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'plain-text-token' => $plainTextToken
        ]);
    }
}
