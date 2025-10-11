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
        Schema::table('perfume_prices', function (Blueprint $table) {
            $table->dropColumn('bottle_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfume_prices', function (Blueprint $table) {
            $table->decimal('bottle_price', 10, 2)->nullable()->after('bottle_size');
        });
    }
};