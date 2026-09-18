<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    protected $fillable = ['item_id', 'user_id', 'type', 'quantity', 'reported_at', 'notes'];

    protected $casts = ['reported_at' => 'date'];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}