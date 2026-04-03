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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_address')->nullable()->after('status');
            $table->string('delivery_city')->nullable()->after('delivery_address');
            $table->string('delivery_postal_code')->nullable()->after('delivery_city');
            $table->string('delivery_phone')->nullable()->after('delivery_postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_city', 'delivery_postal_code', 'delivery_phone']);
        });
    }
};
