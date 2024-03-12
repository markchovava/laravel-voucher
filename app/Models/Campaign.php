<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'vouchers_quantity',
        'reward_name',
        'reward_points',
        'points_per_voucher',
        'price_of_voucher',
        'total_cost',
        'company_name',
        'company_phone',
        'company_address',
        'company_email',
        'company_website',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];



    /** 
     *  RELATIONSHIP
    */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}


