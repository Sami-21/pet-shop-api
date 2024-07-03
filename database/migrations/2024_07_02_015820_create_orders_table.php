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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('order_status_id')->references('id')->on('order_statuses');
            $table->foreignId('payment_id')->nullable()->references('id')->on('payments');
            $table->json('products');
            $table->json('address');
            $table->double('delivery_fee');
            $table->double('amount');
            $table->timestamp('shipped_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
