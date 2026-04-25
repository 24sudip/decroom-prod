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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('phone',20);
            $table->string('email',200)->nullable();
            $table->string('password',200)->nullable();
            $table->longText('address');
            $table->string('tread_name',200)->nullable();
            $table->string('tread_no',200)->nullable();
            $table->string('image',255)->nullable();
            $table->string('verifyToken',255)->nullable();
            $table->decimal('opening_balance',10,2)->default(0);
            $table->decimal('main_balance',10,2)->default(0);
            $table->decimal('due_balance',10,2)->default(0);
            $table->string('device_token',255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
