<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramVoucherResource;
use App\Http\Resources\UserResource;
use App\Models\Campaign;
use App\Models\GeneratedVoucher;
use App\Models\Program;
use App\Models\ProgramVoucher;
use App\Models\RedeemVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
{
    public function generateRandomText($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }
    
    public function index(Request $request){
        if(!empty($request->search)){
            $campaign = Campaign::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $data = Program::with(['user', 'campaign'])
                            ->where('campaign_id', $campaign->id)
                            ->paginate(15);
        } else{
            $data = Program::with(['user', 'campaign'])
                            ->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        }
        return ProgramResource::collection($data);
    }
    public function indexByUserId(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->search)){
            $campaign = Campaign::where('name', 'LIKE', '%' . $request->search . '%')
                            ->first();
            $data = Program::with(['user', 'campaign'])
                            ->where('user_id', $user_id)
                            ->where('campaign_id', $campaign->id)
                            ->paginate(15);
        } else{
            $data = Program::with(['user', 'campaign'])
                            ->where('user_id', $user_id)
                            ->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        }
        return ProgramResource::collection($data);
    }
    public function store(Request $request){
        $user_id = Auth::user()->id;
        $data = Program::where('campaign_id', $request->campaign_id)
                        ->where('user_id', $user_id)->first();
        if(!empty($data)){
            $data->total_points += $request->total_points;
            $data->total_quantity += $request->total_quantity;
            $data->save();
            /*  */
            $voucher = new ProgramVoucher();
            $voucher->user_id = Auth::user()->id;
            $voucher->program_id = $data->id;
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
            $data = new Program();
            $data->user_id = Auth::user()->id;
            $data->campaign_id = $request->campaign_id;
            $data->total_points = $request->total_points;
            $data->total_quantity = $request->total_quantity;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->reward_name = $request->reward_name;
            $data->reward_points = $request->reward_points;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            /*  */
            $voucher = new ProgramVoucher();
            $voucher->user_id = Auth::user()->id;
            $voucher->program_id = $data->id;
            $voucher->campaign_id = $request->campaign_id;
            $voucher->code = $request->code;
            $voucher->points = $request->points;
            $voucher->generated_voucher_id = $request->generated_voucher_id;
            $voucher->created_at = now();
            $voucher->updated_at = now();
            $voucher->save();
            /* DELETE FROM GENERATED VOUCHER */
            GeneratedVoucher::where('id', $request->generated_voucher_id)->delete();

            return response()->json([
                'message' => 'Voucher points added successfully',
            ]);

        }
    }
    public function view($id){
        $data = Program::with(['user', 'campaign'])->find($id);
        if($data->total_points >= $data->reward_points){
            $data->total_points -= $data->reward_points;
            $data->save();
            $redeem_voucher = new RedeemVoucher();
            $redeem_voucher->code = date('Ymd') . $this->generateRandomText();
            $redeem_voucher->user_id = $data->user_id;
            $redeem_voucher->program_id = $data->id;
            $redeem_voucher->campaign_id = $data->user_id;
            $redeem_voucher->reward_points = $data->reward_points;
            $redeem_voucher->status = 'Generated';
            $redeem_voucher->save();
        }
        return response()->json([
         'data' => $data
        ]);
    }


    public function storeByAmount(Request $request){
        if(empty($request->campaign_id)){
            return response()->json([
                'status' => 0,
                'message' => 'Campaign is required.',
            ]);
        }
        if(empty($request->phone)){
            return response()->json([
                'status' => 0,
                'message' => 'Email is required.',
            ]);
        }
        if(empty($request->amount)){
            return response()->json([
                'status' => 0,
                'message' => 'Amount is required.',
            ]);
        }
        if(empty($request->receipt_no)){
            return response()->json([
                'status' => 0,
                'message' => 'Receipt Number is required.',
            ]);
        }
        /* CAMPAIGN */
        $campaign = Campaign::where('id', $request->campaign_id)->first();
        if(empty($campaign)){
            return response()->json([
                'status' => 0,
                'message' => 'Campaign does not exist.',
            ]);
        }
        $user = User::where('phone', $request->phone)->first();
        if(empty($user)){
            return response()->json([
                'status' => 0,
                'message' => 'User does not exist, you are required to register to use the phone number.',
            ]);
        }
        $program = Program::with(['user', 'campaign'])
                    ->where('campaign_id', $campaign->id)
                    ->where('user_id', $user->id)
                    ->first();
            
        if(empty($program)){
            $pre = substr($campaign->name, 0, 3);
            $code = $pre . $this->generateRandomText();
            $calculate_points = (int)$request->amount / $campaign->price_of_voucher;
            $total_points = round($calculate_points);
            /*  */
            $program = new Program();
            $program->user_id = $user->id;
            $program->total_points = $campaign->total_points;
            $program->campaign_id = $request->campaign_id;
            $program->start_date = $campaign->start_date;
            $program->end_date = $campaign->end_date;
            $program->reward_name = $campaign->reward_name;
            $program->reward_points = $campaign->reward_points;
            $program->total_quantity = 1;
            $program->total_points = $total_points;
            $program->created_at = now();
            $program->updated_at = now();
            $program->save();
            /*  */
            $voucher = new ProgramVoucher();
            $voucher->user_id = $user->id;
            $voucher->program_id = $program->id;
            $voucher->campaign_id = $request->campaign_id;
            $voucher->generated_voucher_id = null;
            $voucher->receipt_no = $request->receipt_no;
            $voucher->code = $code;
            $voucher->points = $total_points;
            $voucher->save();
            
            return response()->json([
                'status' => 1,
                'message' => 'A new program added.',
                'campaign' => new CampaignResource($campaign),
                'program' => new ProgramResource($program),
                'voucher' => new ProgramVoucherResource($voucher),
                'user' => new UserResource($user)
            ]);
        }
        /* GENERATE VOUCHER */
        $pre = substr($campaign->name, 0, 3);
        $code = $pre . $this->generateRandomText();
        /* CALCULATE POINTS */
        $calculate_points = (int)$request->amount / $campaign->price_of_voucher;
        $total_points = round($calculate_points) * $campaign->points_per_voucher;
        /* Program */
        $program->user_id = $user->id;
        $program->campaign_id = $request->campaign_id;
        $program->total_quantity += 1;
        $program->total_points += $total_points;
        $program->created_at = now();
        $program->updated_at = now();
        $program->save();
        /* ProgramVoucher */
        $voucher = new ProgramVoucher();
        $voucher->user_id = $user->id;
        $voucher->program_id = $program->id;
        $voucher->campaign_id = $request->campaign_id;
        $voucher->generated_voucher_id = null;
        $voucher->receipt_no = $request->receipt_no;
        $voucher->code = $code;
        $voucher->points = $total_points;
        $voucher->save();          
        return response()->json([
            'status' => 1,
            'message' => 'A Program has been updated.',
            'campaign' => new CampaignResource($campaign),
            'program' => new ProgramResource($program),
            'voucher' => new ProgramVoucherResource($voucher),
            'user' => new UserResource($user)
        ]);
    }


    public function searchByProgramCampaign(Request $request){
        if(!empty($request->search)){
            $campaign = Campaign::where('name', 'LIKE', '%' . $request->search . '%')->first();
            if(empty($campaign)){
                return response()->json([
                    'message' => 'Campaign does not exist.',
                ]);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user)){
                return response()->json([
                    'message' => 'User does not exist.',
                ]);
            }
            $data = Program::with(['user', 'campaign'])
                        ->where('campaign_id', $campaign->id)
                        ->where('user_id', $user->id)
                        ->first();
            if(empty($data)){
                return response()->json([
                    'message' => 'Program does not exist.',
                ]);
            }
            return  response()->json([
                'data' => new ProgramResource($data),
            ]);
        }
        else{
            return response()->json([
                'message' => 'Campaign name is required.',
            ]);
        }
    }

    


}
