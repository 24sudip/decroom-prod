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
        Schema::create('customer_ledger', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->date('transaction_date');
            $table->string('type')->comment('debit, credit');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2)->nullable()->comment('running balance after transaction');
            $table->string('reference')->nullable()->comment('invoice or order reference');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ledger');
    }
};
