<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 15)->nullable();
            $table->enum('order_type', ['DINEIN', 'PARCEL', 'WALKIN']);
            $table->enum('order_source', ['QR_SCAN', 'ONLINE', 'BILLING']);
            $table->enum('status', ['PENDING', 'PREPARING', 'READY', 'SERVED', 'PICKEDUP', 'CANCELLED'])->default('PENDING');
            $table->enum('payment_method', ['ONLINE', 'CASH']);
            $table->enum('payment_status', ['PAID', 'UNPAID'])->default('UNPAID');
            $table->decimal('total_amount', 10, 2);
            $table->string('razorpay_order_id', 255)->nullable();
            $table->string('razorpay_payment_id', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('order_type');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
