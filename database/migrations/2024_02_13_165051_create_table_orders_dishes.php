<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEFT_TABLE_NAME = 'orders';
    private const RIGHT_TABLE_NAME = 'dishes';
    private const PIVOT_TABLE_NAME = self::LEFT_TABLE_NAME . '_' . self::RIGHT_TABLE_NAME;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::PIVOT_TABLE_NAME)) {
            Schema::create(self::PIVOT_TABLE_NAME, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('dish_id');

                $table->foreign('order_id')
                    ->references('id')
                    ->on(self::LEFT_TABLE_NAME)
                    ->onDelete('cascade');
                $table->foreign('dish_id')
                    ->references('id')
                    ->on(self::RIGHT_TABLE_NAME)
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::PIVOT_TABLE_NAME);
    }
};
