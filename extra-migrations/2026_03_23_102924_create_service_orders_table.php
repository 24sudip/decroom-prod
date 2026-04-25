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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->date('expired_at');
            $table->integer('customer_id');
            $table->integer('vendor_user_id');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email');
            $table->decimal('due_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2);
            $table->tinyInteger('installment_number')->default(0);
            $table->tinyInteger('installment_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
