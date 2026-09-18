<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = ['item_id', 'user_id', 'title', 'status', 'cost', 'started_at', 'completed_at', 'notes'];

    protected $casts = ['started_at' => 'date', 'completed_at' => 'date'];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}