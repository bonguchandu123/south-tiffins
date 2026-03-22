<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['ONLINE', 'CASH']);
            $table->enum('payment_status', ['PAID', 'UNPAID', 'FAILED', 'REFUNDED'])->default('UNPAID');
            $table->string('razorpay_order_id', 255)->nullable();
            $table->string('razorpay_payment_id', 255)->nullable();
            $table->string('razorpay_signature', 500)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};