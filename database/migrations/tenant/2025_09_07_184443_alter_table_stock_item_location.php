<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'item_location_id')) {
                $table->unsignedBigInteger('item_location_id')->after('product_id')->nullable();
                $table->foreign('item_location_id')
                    ->references('id')
                    ->on('item_locations')
                    ->restrictOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('item_location');
        });
    }
};
