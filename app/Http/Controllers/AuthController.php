<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($data);
        $token = $user->createToken('full', ['books:read', 'books:write'])->plainTextToken;

        return response()->json(['message' => 'Registered.', 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('full', ['books:read', 'books:write'])->plainTextToken;

        return response()->json(['message' => 'Logged in.', 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function readOnlyToken(Request $request)
    {
        $token = $request->user()->createToken('readonly', ['books:read'])->plainTextToken;

        return response()->json(['message' => 'Read-only token created.', 'token' => $token], 201);
    }
}