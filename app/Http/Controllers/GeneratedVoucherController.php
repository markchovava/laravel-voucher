<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneratedVoucherResource;
use App\Models\Campaign;
use App\Models\GeneratedVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GeneratedVoucherController extends Controller
{

    public function generateRandomText($length = 9) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function index(Request $request){
        if(!empty($request->search)){
            $data = GeneratedVoucher::where('code', 'LIKE', '%' . $request->search . '%')
                                    ->paginate(15);
        } else{
            $data = GeneratedVoucher::paginate(15);
        }
        return GeneratedVoucherResource::collection($data);
    }

    public function indexById(Request $request, $id){
        if(!empty($request->search)){
            $data = GeneratedVoucher::with(['user', 'campaign'])->where('campaign_id', $id)
                                    ->where('code', 'LIKE', '%' . $request->search . '%')
                                    ->paginate(20);
        } else{
            $data = GeneratedVoucher::with(['user', 'campaign'])
                                    ->where('campaign_id', $id)
                                    ->paginate(20);
        }
        return GeneratedVoucherResource::collection($data);
    }

    public function store(Request $request){
        $pre = substr($request->name, 0, 3);
        for ($i = 0; $i < $request->vouchers_quantity; $i++) {
            $code = $pre . $this->generateRandomText();
            $data = new GeneratedVoucher();
            $data->user_id = Auth::user()->id;
            $data->code = $code;
            $data->campaign_id = $request->campaign_id;
            $data->save();
        }

        return response()->json([
            'message' => 'Vouchers created successfully'
        ]);
    
    }

    public function voucherSearch(Request $request){
        if(!empty($request->search)){
            $voucher = GeneratedVoucher::where('code', $request->search)->first();
            if(!empty($voucher)){
             $campaign = Campaign::where('id', $voucher->campaign_id)->first();
             return response()->json([
                 'voucher' => $voucher,
                 'campaign' => $campaign
             ]);
            } else{
                 return response()->json([
                     'message' => 'Oops, Invalid Voucher...',
                 ]);
            }
        } else{
            return response()->json([
                'message' => 'Please enter your voucher.',
            ]);
        }
        //Log::info('ugiug');
        return 'Tets';
    }

    public function checkIfExists($id){
        $data = GeneratedVoucher::where('campaign_id',$id)->first();
        if(!empty($data)){
            return response()->json([
                'data' => 1
            ]);
        } else {
            return response()->json([
                'data' => 0
            ]);
        }
    }

}
