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
        Schema::create('stockouts', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('delivery_note_id');
            $table->date('date');
            $table->string('reference');
            $table->text('note')->nullable();
            $table->integer('total');
            $table->foreign('delivery_note_id')->references('id')
                ->on('delivery_notes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockouts');
    }
};
