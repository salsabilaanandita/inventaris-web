<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'location_id', 'unit_id', 'supplier_id', 'price', 'total', 'repair'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke tabel lending (peminjaman)
    public function lendings() {
        return $this->hasMany(Lending::class);
    }

    // Accessor untuk hitung 'Lending Total' (jumlah yang sedang dipinjam)
    public function getLendingTotalAttribute() {
        return $this->lendings()->count(); 
    }

    // Accessor untuk hitung 'Available' (Stok yang tersedia)
    public function getAvailableAttribute() {
        // Rumus: Total - Repair - Lending Total
        return $this->total - $this->repair - $this->lending_total;
    }
}