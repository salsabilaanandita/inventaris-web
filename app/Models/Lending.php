<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    // Tambahkan baris ini!
    protected $fillable = [
        'item_id', 
        'user_id', 
        'name', 
        'total', 
        'notes', 
        'return_date'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}