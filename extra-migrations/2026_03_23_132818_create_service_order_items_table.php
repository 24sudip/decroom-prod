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
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('service_order_id');
            $table->integer('service_id');
            $table->integer('vendor_user_id');
            $table->decimal('total_cost', 8, 2);
            $table->decimal('material_cost', 8, 2);
            $table->decimal('service_charge', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('vendor_earning', 10, 2);
            $table->decimal('admin_commission', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_items');
    }
};
