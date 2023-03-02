<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:15',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()->all()],405);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user
        ], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()->all()],400);
        }

		$token = null;
		
        if ($token = JWTAuth::attempt($validator->validated())) {
            return response()->json([
                'response' => 'Success',
                'result' => [
                    'token' => $token,
                ],
            ]);
        }
        return response()->json([
            'response' => 'Error',
            'message' => 'Invalid username or password',
        ], 400);
    }

    public function logout()
    {   
        if (Auth::check() == false)
        {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        Auth::guard('api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}