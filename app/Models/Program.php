<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'total_points',
        'total_quantity',
        'start_date',
        'end_date',
        'reward_points',
        'reward_name',
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
