<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'email',
        'password',
        'created_at',
        'updated_at',
    ];

    public function campaigns(){
        return $this->hasMany(Campaign::class, 'campaign_id', 'id');
    }
    
    public function claimed_vouchers(){
        return $this->hasMany(ClaimedVoucher::class, 'customer_id', 'id');
    }

}
