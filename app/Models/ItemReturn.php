<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReturn extends Model
{
    protected $table = 'item_returns';
    protected $fillable = ['lending_id', 'item_id', 'user_id', 'quantity', 'returned_at', 'notes'];

    protected $casts = ['returned_at' => 'datetime'];

    public function lending() { return $this->belongsTo(Lending::class); }
    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}