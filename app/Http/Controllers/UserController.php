<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function generateRandomText($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function index(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->search)){
            $data = User::where('name', 'LIKE', '%' . $request->search . '%')
                    ->paginate(15);
        } else{
            $data = User::whereNot('id', $user_id)->orderBy('updated_at', 'desc')
                    ->paginate(15);
        }
   
        return UserResource::collection($data);
    }

    public function store(Request $request){
        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            return response()->json([
                'status' => 0,
                'message' => 'Email is already taken, please try another one.',
            ]);
        }
        $user = User::where('phone', $request->phone)->first();
        if(isset($user)){
            return response()->json([
                'status' => 2,
                'message' => 'Phone number is already taken, please try another one.',
            ]);
        }
        $code = $this->generateRandomText();
        $data = new User();
        $data->name = $request->name;
        $data->dob = $request->dob;
        $data->gender = $request->gender;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->role_level = $request->role_level;
        $data->code = $code;
        $data->password = Hash::make($code);
        $data->address = $request->address;
        $data->id_number = $request->id_number;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'status' => 1,
            'message' => 'Saved Successfully.',
            'data' => new UserResource($data)
        ]);
    }

    public function update(Request $request, $id){
        $user = User::where('id', '!=', $id)
                ->where('email', $request->email)->first();
        Log::info($user);
        if( isset($user) ){
            return response()->json([
                'status' => 0,
                'message' => 'Email is already taken, please try another one.',
            ]);
        }
        $user = User::where('id', '!=', $id)
                ->where('phone', $request->phone)->first();
        Log::info($user);
        if(isset($user)){
            return response()->json([
                'status' => 2,
                'message' => 'Phone number is already taken, please try another one.',
            ]);
        }
        $data = User::find($id);
        $data->name = $request->name;
        $data->dob = $request->dob;
        $data->gender = $request->gender;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->role_level = $request->role_level;
        $data->address = $request->address;
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'status' => 1,
            'message' => 'Saved Successfully.',
            'data' => new UserResource($data)
        ]);
    }

    public function view($id){
        $data = User::with(['role'])->find($id);
        return new UserResource($data);
    }

    public function delete($id){
        $data = User::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Deleted Successfully.'
        ]);
    }
}
