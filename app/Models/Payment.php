<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'method',
        'status',
        'amount',
        'currency',
        'transaction_id',
        'data',
        'metadata',
        'provider_response'
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'amount' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
