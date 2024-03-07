<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'campaign_id',
        'generated_voucher_id',
        'points',
        'code',
        'created_at',
        'updated_at',
    ];


    /** 
     *  RELATIONSHIP
    */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function program(){
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function generated_voucher(){
        return $this->belongsTo(GeneratedVoucher::class, 'generated_voucher_id', 'id');
    }
}
