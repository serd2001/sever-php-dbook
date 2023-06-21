<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


use Kreait\Laravel\Firebase\Facades\FirebaseAuth;

class AuthController extends Controller
{
    use Authenticatable;

    // public function register(Request $request)
    // {
    // $validator = Validator::make($request->all(), [
    //     'name' => 'required|string|max:255',
    //     'email' => 'required|string|email|max:255|unique:users',
    //     'password' => 'required|string|min:6|confirmed',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json($validator->errors(), 400);
    // }

    // $user = User::create([
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'password' => bcrypt($request->password),
    // ]);

    // $token = $user->createToken('Personal Access Token')->accessToken;

    // return response()->json(['token' => $token], 201);
    //}

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'firebase_token' => 'required|string', // Firebase ID token from frontend
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $auth = app('firebase.auth');

        // Verify the Firebase ID token
        try {
            $verifiedIdToken = $auth->verifyIdToken($request->firebase_token);
            $userId = $verifiedIdToken->claims('sub'); // Get the user ID from the token
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid Firebase ID token'], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),

        ]);
        $token = $user->createToken('Personal Access Token')->accessToken;


        return response()->json(['token' => $token->accessToken], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        $user = User::where('phone', $credentials['phone'])->first();

        if (isset($user) && Hash::check($credentials['password'] ?? '',  $user->password)) {
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('phone', 'password');

    //     // if (Auth::attempt($credentials)) {
    //     //     $token = auth()->user()->createToken('Personal Access Token')->accessToken;
    //     //     return response()->json(['token' => $token], 200);
    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         $token = $user->createToken('Personal Access Token')->accessToken;
    //         return response()->json(['token' => $token], 200);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    // public function user(Request $request)
    // {
    //     return response()->json($request->user());
    // }
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('phone', 'password');

    //     if (Auth::guard('api')->attempt($credentials)) {
    //         $user = User::where('phone', $request->input('phone'))->first();
    //         $token = $user->createToken('Personal Access Token')->accessToken;
    //         return response()->json(['token' => $token], 200);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
