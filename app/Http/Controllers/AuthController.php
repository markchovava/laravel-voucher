<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function view(){
        $user_id = Auth::user()->id;
        $data = User::with(['role'])->find($user_id);
        return new UserResource($data);
    }

    public function checkUserById($id){
        $data = User::find($id);
        return response()->json([
            'token' => !empty($data) ? 1 : 0,
            'data' => new UserResource($data),
        ]);
    }
    
    public function update(Request $request){
        $user_id = Auth::user()->id;
        $data = User::find($user_id);
        $data->name = $request->name;
        $data->dob = $request->dob;
        $data->gender = $request->gender;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->role_level = $request->role_level;
        $data->address = $request->address;
        $data->id_number = $request->id_number;
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new UserResource($data)
        ]);
    }
    
    public function password(Request $request){
        $user_id = Auth::user()->id;
        $data = User::find($user_id);
        $data->code = $request->password;
        $data->password = Hash::make($request->password);
        $data->save();

        return response()->json([
            'message' => 'Updated Successfully.',
        ]);
    }
 
    public function login(Request $request){
        
        $user = User::where('email', $request->email)->first();
        if(!isset($user)){
            return response()->json([
                'message' => 'Email is not found.',
                'error' => 401,
                'status' => 0,
            ]);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Password does not match.',
                'error' => 401,
                'status' => 2,
            ]);
        }
        return response()->json([
            'message' => 'Login Successfully.',
            'auth_token' => $user->createToken($user->email)->plainTextToken,
            'role_level' => !empty($user->role_level) ? $user->role_level : 4,
            'user_id' => $user->id,
            'status' => 1,
        ]);

    }

    public function register(Request $request){
        if(User::where('email', $request->email)->first()){
            return response()->json([
                'status' => 0,
                'message' => 'Email is already used, please try a different one.',
            ]);
        }
        if(User::where('phone', $request->phone)->first()){
            return response()->json([
                'status' => 2,
                'message' => 'Phone Number is already used, please try a different one.',
            ]);
        }
        $data = new User();
        $data->role_level = isset($request->role_level) ? $request->role_level : 4;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->code = $request->password;
        $data->password = Hash::make($request->password);
        $data->save();

        return response()->json([
            'status' => 1,
            'message' => 'Created Successfully.',
        ]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have been succesfully logged out.',
        ]);
        
    }
}
