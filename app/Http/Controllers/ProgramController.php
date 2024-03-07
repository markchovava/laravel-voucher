<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgramResource;
use App\Models\Campaign;
use App\Models\GeneratedVoucher;
use App\Models\Program;
use App\Models\ProgramVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ProgramController extends Controller
{
    
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
        return response()->json([
         'data' => $data
        ]);
    }


}
