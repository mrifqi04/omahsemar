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
        Schema::create('stockout_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stockout_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->integer('qty_out');
            $table->integer('qty_stockout');
            $table->integer('total');
            $table->text('note')->nullable();
            $table->foreign('stockout_id')->references('id')
                ->on('stockouts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')
                ->on('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stockout_details', function (Blueprint $table) {
            //
        });
    }
};
