<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'code',
        'campaign_id',
        'created_at',
        'updated_at',
    ];


    /** 
     *  RELATIONSHIP
    */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

}
