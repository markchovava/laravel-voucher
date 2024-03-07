<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'campaign_id',
        'program_id',
        'reward_points',
        'status',
        'code',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
    public function program(){
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

}
