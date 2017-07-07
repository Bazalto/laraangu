<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function signup(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        $user -> save();
        return response()->json([
            'message' => 'Success'
        ], 201);

    }

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credientals = $request->only('email', 'password');
        try{
            if(!$token = JWTAuth::attempt($credientals)){
                return response()->json([
                    'error' => 'Invalid Credential'
                ], 401);
            }
        }catch (JWTException $e){
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }

        return response()->json([
            'token' => $token
        ], 200);
    }
}
