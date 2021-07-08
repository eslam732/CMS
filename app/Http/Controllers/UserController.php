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
    public function getUsers()
    {
        if(!auth()->user()->isAdmin()){
            return response()->json(['error' => 'UnAuthorized'], 203);
        }
        return response()->json(['users'=>User::all()],200);
    }
    public function makeAdmin($userId)
    {
        //$allData=request()->all();
        if(!auth()->user()->isAdmin()){
            return response()->json(['error' => 'UnAuthorized'], 203);
        }
        $user=User::find($userId);
     if($user->role=='admin'){
         return response()->json(['message' => 'this user is already an admin'], 203);
     }
     $user->role='admin';
     $user->save();
     return response()->json(['message' => 'user is now an an admin'], 200);
    }
    public function getUserProfile()
    {   $id=auth()->user()->id;
        $user=User::find($id);
        return response()->json(['message' => $user], 200);
    }
    public function editUserProfile()
    {   $id=auth()->user()->id;
        $user=User::find($id);
        $validation = Validator::make(request()->all(), [
            'name' => 'required',
            
            'about' => 'required',
           

        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        $allData=request()->all();
        $user->update([
            'name' => $allData['name'],
            'about' => $allData['about'],
        ]);

        return response()->json(['message' => $user], 200);
    }
}
