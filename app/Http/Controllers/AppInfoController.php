<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppInfoResource;
use App\Models\AppInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppInfoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if(AppInfo::first()){
            $data = AppInfo::first();
            $data->user_id = Auth::user()->id;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->address = $request->address;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->website = $request->website;
            $data->whatsapp = $request->whatsapp;
            $data->facebook = $request->facebook;
            $data->updated_at = now();
            $data->save();

        } else {
            $data = new AppInfo();
            $data->user_id = Auth::user()->id;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->address = $request->address;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->website = $request->website;
            $data->whatsapp = $request->whatsapp;
            $data->facebook = $request->facebook;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
        }

        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new AppInfoResource($data),
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function view()
    {
        $appinfo = AppInfo::first();
        if(!empty($appinfo)){
            $data = AppInfo::with(['user'])->first();
            return response()->json([
                'data' => new AppInfoResource($data),
            ]);
        } else{
            return response()->json([
                'message' => 'Data not yet added.',
            ]);
        }


    }

   

}
