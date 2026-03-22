<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->unique();
            $table->integer('total_orders')->default(0);
            $table->integer('dinein_orders')->default(0);
            $table->integer('parcel_orders')->default(0);
            $table->integer('walkin_orders')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('cash_revenue', 10, 2)->default(0);
            $table->decimal('online_revenue', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};