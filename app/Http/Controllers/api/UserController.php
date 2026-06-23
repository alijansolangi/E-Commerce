<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // 1. Validate inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Generate access token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Return response
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // 1. Validate inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Verify user credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }

        // 3. Generate access token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }


    //AdminLogin Function

    public function AdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 🔥 Check role and redirect
            $user = Auth::user();

            if ($user->role == "admin") {
                return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function Adminlogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Handle user logout (Revoke token).
     */
    public function logout(Request $request)
    {
        // Revoke the current token being used for the request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully and token deleted'
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
