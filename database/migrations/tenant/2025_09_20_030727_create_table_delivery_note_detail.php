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
        Schema::create('delivery_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_note_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('unit_price');
            $table->integer('sub_total');
            $table->integer('product_discount_amount');
            $table->string('product_discount_type')->default('fixed');
            $table->integer('product_tax_amount');
            $table->foreign('delivery_note_id')->references('id')
                ->on('delivery_notes')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')
                ->on('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('delivery_note_details');
    }
};
