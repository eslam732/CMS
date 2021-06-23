<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{

    public function try()
    {
        dd('5');
    }
    public function registration(Request $request)

    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'CPassword' => 'required|same:password',

        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $resArr = [];
        $resArr['token'] = $user->createToken('api-application')->accessToken;
        $resArr['name'] = $user->name;

        return response()->json($resArr, 200);
    }
    public function login(Request $request)
    {

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $user = Auth::user();
            $resArr = [];
            $resArr['token'] = $user->createToken('api-application')->accessToken;
            $resArr['name'] = $user->name;
            $resArr['email'] = $user->email;
            $resArr['id'] = $user->id;
        } else {
            return response()->json(['error' => 'UnAuthorized'], 203);
        }
        return response()->json($resArr, 200);
    }
}
