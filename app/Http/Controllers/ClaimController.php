<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClaimResource;
use App\Models\Campaign;
use App\Models\Claim;
use App\Models\ClaimedVoucher;
use App\Models\GeneratedVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{
    
    public function index(Request $request){
        if(!empty($request->search)){
            $campaign = Campaign::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $data = Claim::with(['user', 'campaign'])->where('campaign_id', $campaign->id)
                            ->paginate(15);
        } else{
            $data = Claim::with(['user', 'campaign'])->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        }
        return ClaimResource::collection($data);
    }

    public function indexUserId(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->search)){
            $campaign = Campaign::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $data = Claim::with(['user', 'campaign'])
                            ->where('user_id', $user_id)
                            ->where('campaign_id', $campaign->id)
                            ->paginate(15);
        } else{
            $data = Claim::with(['user', 'campaign'])
                            ->where('user_id', $user_id)
                            ->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
            $data = Claim::with(['user', 'campaign'])->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        } 
        
        return ClaimResource::collection($data);
    }

    public function store(Request $request){
        $user_id = Auth::user()->id;
        $claim = Claim::where('campaign_id', $request->campaign_id)
                        ->where('user_id', $user_id)->first();
        if(!empty($claim)){
            $claim->total_points += $request->total_points;
            $claim->total_quantity += $request->total_quantity;
            $claim->save();
            /*  */
            $voucher = new ClaimedVoucher();
            $voucher->user_id = Auth::user()->id;
            $voucher->claim_id = $claim->id;
            $voucher->campaign_id = $request->campaign_id;
            $voucher->code = $request->code;
            $voucher->points = $request->points;
            $voucher->generated_voucher_id = $request->generated_voucher_id;
            $voucher->save();
            /* DELETE FROM GENERATED VOUCHER */
            GeneratedVoucher::where('id', $request->generated_voucher_id)->delete();

            return response()->json([
                'message' => 'Voucher points updated successfully',
            ]);

        } else{
            $claim = new Claim();
            $claim->user_id = Auth::user()->id;
            $claim->campaign_id = $request->campaign_id;
            $claim->total_points = $request->total_points;
            $claim->total_quantity = $request->total_quantity;
            $claim->start_date = $request->start_date;
            $claim->end_date = $request->end_date;
            $claim->created_at = now();
            $claim->updated_at = now();
            $claim->save();
            /*  */
            $voucher = new ClaimedVoucher();
            $voucher->user_id = Auth::user()->id;
            $voucher->claim_id = $claim->id;
            $voucher->campaign_id = $request->campaign_id;
            $voucher->code = $request->code;
            $voucher->points = $request->points;
            $voucher->generated_voucher_id = $request->generated_voucher_id;
            $voucher->save();
            /* DELETE FROM GENERATED VOUCHER */
            GeneratedVoucher::where('id', $request->generated_voucher_id)->delete();

            return response()->json([
                'message' => 'Voucher points added successfully',
            ]);

        }
    }

    public function update(Request $request, $id){
        
    }

    public function view($id){
       $data = Claim::with(['user', 'campaign'])->find($id);
       return response()->json([
        'data' => $data
       ]);
    }

    public function delete($id){
        $data = Claim::with(['user', 'campaign'])->find($id);
        return response()->json([
            'message' => 'Deleted successfully.'
        ]);
    }

}
