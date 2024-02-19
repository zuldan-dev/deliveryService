<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_NAME = 'orders';
    private const FIELD_NAME = 'driver_id';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn(self::TABLE_NAME, self::FIELD_NAME)) {
            Schema::table(self::TABLE_NAME, function (Blueprint $table) {
                $table->unsignedBigInteger(self::FIELD_NAME)->nullable()->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(self::TABLE_NAME, self::FIELD_NAME)) {
            Schema::table(self::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(self::FIELD_NAME);
            });
        }
    }
};
