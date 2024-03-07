<?php

namespace App\Http\Controllers;

use App\Http\Resources\RedeemVoucherResource;
use App\Models\Program;
use App\Models\RedeemVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedeemVoucherController extends Controller
{

    public function generateRandomText($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function index(Request $request){
        if(!empty($request->search)){
            $data = RedeemVoucher::with(['user', 'program', 'campaign'])
                                ->where('code', 'LIKE', '%' . $request->search . '%')
                                ->paginate(10);
        } else{
            $data = RedeemVoucher::with(['user', 'program', 'campaign'])->paginate(10);
        }
        return RedeemVoucherResource::collection($data);
    }

    public function searchView(Request $request){
        $data = RedeemVoucher::with(['user', 'program', 'campaign'])
                                ->where('code', 'LIKE', '%' . $request->search . '%')
                                ->first();
       
        return new RedeemVoucherResource($data);
    }


    public function indexByProgramId(Request $request){
        if(!empty($request->search)){
            $data = RedeemVoucher::with(['user', 'program', 'campaign'])
                                ->where('code', 'LIKE', '%' . $request->search . '%')
                                ->where('user_id', $request->user_id)
                                ->where('program_id', $request->program_id)
                                ->paginate(10);
        } else {
            $data = RedeemVoucher::with(['user', 'program', 'campaign'])
                                ->where('program_id', $request->program_id)
                                ->paginate(10);
        }
        return RedeemVoucherResource::collection($data);
    }

    public function store(Request $request){
        $code = date('Ymd') . $this->generateRandomText();
        $data = new RedeemVoucher();
        $data->code = $code;
        $data->user_id = $request->user_id;
        $data->campaign_id = $request->campaign_id;
        $data->program_id = $request->program_id;
        $data->reward_points = $request->reward_points;
        $data->status = 'Generated';
        $data->save();

        $program = Program::where('id', $request->program_id)->first();
        $program->total_points = (int)$program->total_points - (int)$program->reward_points;
        $program->save();
        
        return response()->json([
            'message' => 'Voucher created successfully.',
            'data' => new RedeemVoucherResource($data),
        ]);
    }

    public function view($id){
        $data = RedeemVoucher::with(['user', 'program', 'campaign'])->find($id);
        return new RedeemVoucherResource($data);
    }

    public function checkIfExists(Request $request){
        $data = RedeemVoucher::where('user_id', $request->user_id)
                            ->where('program_id', $request->program_id)
                            ->first();
                            
        return response()->json([
            'data' => !empty($data) ? 1 : 0,
        ]);
    }
}
