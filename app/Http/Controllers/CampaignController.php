<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(Request $request){
        if(!empty($request->search)){
            $data = Campaign::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->paginate(15);
        } else{
            $data = Campaign::with(['user'])
                    ->orderBy('name', 'asc')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(15);
        }
        return CampaignResource::collection($data);
    }


    public function store(Request $request){
        $data = new Campaign();
        $data->status = 'Processing';
        $data->user_id = Auth::user()->id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->vouchers_quantity = $request->vouchers_quantity;
        $data->reward_name = $request->reward_name;
        $data->reward_points = $request->reward_points;
        $data->points_per_voucher = $request->points_per_voucher;
        $data->total_cost = $request->total_cost;
        $data->price_of_voucher = $request->price_of_voucher;
        $data->company_name = $request->company_name;
        $data->company_phone = $request->company_phone;
        $data->company_address = $request->company_address;
        $data->company_email = $request->company_email;
        $data->company_website = $request->company_website;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new CampaignResource($data)
        ]);
    }


    public function update(Request $request, $id){
        $data = Campaign::find($id);
        $data->status = $request->status;
        $data->user_id = Auth::user()->id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->vouchers_quantity = $request->vouchers_quantity;
        $data->reward_name = $request->reward_name;
        $data->reward_points = $request->reward_points;
        $data->points_per_voucher = $request->points_per_voucher;
        $data->total_cost = $request->total_cost;
        $data->price_of_voucher = $request->price_of_voucher;
        $data->company_name = $request->company_name;
        $data->company_phone = $request->company_phone;
        $data->company_address = $request->company_address;
        $data->company_email = $request->company_email;
        $data->company_website = $request->company_website;
        $data->updated_at = now();
        $data->save();

        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new CampaignResource($data)
        ]);
    }


    public function update_status(Request $request, $id){
        $data = Campaign::find($id);
        $data->status = $request->status;
        $data->save();

        return response()->json([
            'message' => 'Saved Successfully.',
            'data' => new CampaignResource($data)
        ]);
    }


    public function view($id){
        $data = Campaign::with(['user'])->find($id);
        return new CampaignResource($data);
    }


    public function delete($id){
        $data = Campaign::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Deleted Successfully.'
        ]);
    }

    
}
