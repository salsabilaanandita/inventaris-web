<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('category_id')->constrained('locations')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('location_id')->constrained('units')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->after('unit_id')->constrained('suppliers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['location_id', 'unit_id', 'supplier_id']);
        });
    }
};
