<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('generic_id');
            $table->string('name',255);
            $table->string('batch_no',255);
            $table->string('slug')->nullable();
            $table->string('product_code');
            $table->string('image', 255)->nullable();
            $table->string('pdf_file', 255)->nullable();
            $table->string('product_type');
            $table->decimal('purchase_price',10,2)->nullable();
            $table->decimal('old_price',10,2)->nullable();
            $table->decimal('new_price',10,2);
            $table->integer('stock');
            $table->tinyInteger('trending')->nullable();
            $table->tinyInteger('offer')->nullable();
            $table->tinyInteger('new_arival')->nullable();
            $table->tinyInteger('flash')->nullable();
            $table->date('manufacturer_date');
            $table->date('expire_date');
            $table->string('exp_limit');
            $table->string('status')->default(1)->comment('1=>Active,0=>inactive');
            $table->tinyInteger('is_deleted')->default(0)->comment('0=>active,1=>inactive');

            $table->foreign('category_id')->references('id')->on('product_categories');
            $table->foreign('brand_id')->references('id')->on('product_brands');
            $table->foreign('unit_id')->references('id')->on('unites');
            $table->foreign('generic_id')->references('id')->on('generics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
