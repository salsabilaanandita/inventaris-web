<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('type', 30);
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->dateTime('transaction_at');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('stock_histories'); }
};