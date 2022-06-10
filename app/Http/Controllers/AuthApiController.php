<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255',
            'password' => 'required|string'
        ]);

        // check if validation rules failed
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'An error occurred',
                'request' => $request->all()
            ], 500);
        }

        $loginCredentials = $request->only('email', 'password');

        if (! Auth::attempt($loginCredentials)) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid login details'
            ], 422);
        }

        $token = Auth::user()->createToken('authToken')->accessToken;

//        if (Auth::user() && Auth::user()->email !== "admin@admin.com") {
//            dispatch(new LoginStaffAlertJob(Auth::user()));
//        }

        return response()->json([
            'message' => 'Login Successful',
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => Auth::user(),
            ]
        ]);
    }
}
