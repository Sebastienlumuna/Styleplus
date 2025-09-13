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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method'); // ex: 'orange_money','mpesa','carte_bancaire'
            $table->string('status')->default('pending'); // pending, processing, paid, failed, refunded
            $table->unsignedBigInteger('amount'); // stocker en centimes -> 100 = 1.00
            $table->string('currency', 3)->default('USD'); // ou 'CDF' selon toi
            $table->string('transaction_id')->nullable();
            $table->json('data')->nullable(); // phone, card_last4, etc.
            $table->json('metadata')->nullable();
            $table->longText('provider_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
