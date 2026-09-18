<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = ['item_id', 'user_id', 'system_stock', 'actual_stock', 'opname_date', 'notes'];

    protected $casts = ['opname_date' => 'date'];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}