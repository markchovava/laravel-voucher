<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClaimedVoucherResource;
use App\Models\ClaimedVoucher;
use App\Models\RedeemVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimedVoucherController extends Controller
{

    public function index(Request $request){
        if(!empty($request->search)){
            $data = ClaimedVoucher::with(['user', 'program', 'campaign'])
                        ->where('code',  $request->search )
                        ->paginate(10);
        }else{
            $data = ClaimedVoucher::with(['user', 'program', 'campaign'])->paginate(10);
        }
        return ClaimedVoucherResource::collection($data);
    }
    
    public function store(Request $request){
        $data = new ClaimedVoucher();
        $data->code = $request->code;
        $data->user_id = Auth::user()->id;
        $data->campaign_id = $request->campaign_id;
        $data->program_id = $request->program_id;
        $data->reward_points = $request->reward_points;
        $data->status = 'Used';
        $data->save();

        $redeemData = RedeemVoucher::find($request->redeem_voucher_id);
        $redeemData->delete();
        
        return response()->json([
            'message' => 'Voucher saved successfully.',
            'data' => new ClaimedVoucherResource($data),
        ]);
    }

    public function view($id){
        $data = ClaimedVoucher::with(['user', 'program', 'campaign'])->find($id);
        return new ClaimedVoucherResource($data);
    }

}
