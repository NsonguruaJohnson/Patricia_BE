<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registerUser(Request $request) {

        $this->validate($request, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed'] // Have a password_confirmation field
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if (!$user) {

            $info = [
                'status' => 'error',
                'message' => 'Unable to create User'
            ];
            return response()->json($info, 400);
        }

        $info = [
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ];

        return response()->json($info, 201);


    }

    public function loginUser(Request $request){

        // $credentials = collect($request->only('username', 'password'));
        // dd($credentials);

        $this->validate($request, [
            'username' => 'required',
            'password' => ['required']
        ]);

        $user = Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);


        if (!$user){

            $info = [
                'status' => 'error',
                'message' => 'Invalid login details'
            ];

            return response()->json($info, 422);
        }

        auth()->user()->update([
            'api_token' => Str::random(10)
        ]);

        $info = [
            'status' => 'success',
            'message' => 'User login successful',
            'user' => auth()->user()
        ];

        return  response()->json($info, 200);

    }

    public function showUser($id, Request $request) {
        // dd($user);
        $user = User::find($id);


        if (!$user) {

            $info = [
                'status' => 'error',
                'message' => 'User not found'
            ];

            return response()->json($info, 404);
        }

        // checks and compares the api_tokens of the user and the request
        if($user->api_token !== $request->bearerToken()) {
            return response('Unauthorised', 409); #409 is a conflict http code
        }

        $info = [
            'status' => 'success',
            'message' => 'User found',
            'user' => $user
        ];

        return response()->json($info, 200);
    }

    public function updateUser($id, Request $request) {

        // dd($request);

        $this->validate($request, [
            'username' => ['unique:users,username'],
            'email' => ['email', 'unique:users,email'],
        ]);

        $user = User::find($id);

        if(!$user) {

            $info = [
                'status' => 'error',
                'message' => 'User not found'
            ];

            return response()->json($info, 404);

        }

        if($user->api_token !== $request->bearerToken()) {
            return response('Unauthorised', 409); #409 is a conflict http code
        }

        $user->update([
            'first_name' => is_null($request->first_name) ? $user->first_name : $request->first_name,
            'last_name' => is_null($request->last_name) ? $user->last_name : $request->last_name,
            'username' => is_null($request->username) ? $user->username : $request->username,
            'email' => is_null($request->email) ? $user->email : $request->email,
        ]);

        $info = [
            'status' => 'success',
            'message' => 'User details updated',
            'user' => $user
        ];

        return response()->json($info, 200);
    }

    public function destroyUser($id, Request $request) {

        $user = User::find($id);

        if (!$user) {
            $info = [
                'status' => 'error',
                'message' => 'User not found'
            ];

            return response()->json($info, 422);
        }

        if($user->api_token !== $request->bearerToken()) {
            return response('Unauthorised', 409); #409 is a conflict http code
        }

        $user->delete();

        $info = [
            'status' => 'success',
            'message' => 'User deleted'
        ];
        return response()->json($info, 200);
    }
}
