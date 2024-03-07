<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'quantity',
        'price',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
