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
        Schema::create('service_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('service_video');
            $table->decimal('project_cost', 8, 2);
            $table->decimal('service_charge', 8, 2);
            $table->decimal('material_cost', 8, 2);
            $table->text('delivery_duration');
            $table->decimal('discount', 8, 2);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_drafts');
    }
};
