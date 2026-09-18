<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = ['item_id', 'user_id', 'quantity', 'received_at', 'notes'];

    protected $casts = ['received_at' => 'date'];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}