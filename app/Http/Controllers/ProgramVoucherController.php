<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgramVoucherResource;
use App\Models\ProgramVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramVoucherController extends Controller
{
    public function indexByProgramId($id){
        $data = ProgramVoucher::with(['user', 'program', 'campaign'])
                                ->where('campaign_id', $id)
                                ->paginate(10);
        return ProgramVoucherResource::collection($data);
    }

    public function indexByProgramUserId($id){
        $user_id  = Auth::user()->id;
        $data = ProgramVoucher::with(['user', 'program', 'campaign'])
                                ->where('user_id', $user_id)
                                ->where('campaign_id', $id)
                                ->paginate(10);
        return ProgramVoucherResource::collection($data);
    }
}
