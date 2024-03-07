<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherPriceResource;
use App\Models\VoucherPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoucherPriceController extends Controller
{
    
    public function store(Request $request){
        if(VoucherPrice::first()){
            Log::info('Exists');
            $data = VoucherPrice::first();
            $data->user_id = Auth::user()->id;
            $data->price = $request->price;
            $data->quantity = $request->quantity;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
        } else {
            Log::info('New');
            $data = new VoucherPrice();
            $data->user_id = Auth::user()->id;
            $data->price = $request->price;
            $data->quantity = $request->quantity;
            $data->updated_at = now();
            $data->save();
        }
        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new VoucherPriceResource($data)
        ]);

    }

    public function view(){
        $data = VoucherPrice::with(['user'])->first();
        return new VoucherPriceResource($data);
    }

}
