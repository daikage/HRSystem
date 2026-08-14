<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove any pre-existing duplicate (user_id, date) rows so the unique
        // index below can be created, keeping the earliest clock-in per day.
        Schema::table('attendance_records', function (Blueprint $table) {
            $driver = Schema::getConnection()->getDriverName();

            $withoutBackup = match ($driver) {
                'pgsql', 'sqlsrv' => 'DELETE FROM attendance_records a USING attendance_records b '
                    . 'WHERE a.user_id = b.user_id AND a.date = b.date AND a.id > b.id',
                'mysql' => 'DELETE a FROM attendance_records a '
                    . 'INNER JOIN attendance_records b '
                    . 'ON a.user_id = b.user_id AND a.date = b.date AND a.id > b.id',
                // sqlite does not support UPDATE/DELETE with JOIN.
                default => 'DELETE FROM attendance_records WHERE id NOT IN ('
                    . 'SELECT MIN(id) FROM attendance_records GROUP BY user_id, date)',
            };

            DB::statement($withoutBackup);
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'date']);
        });
    }
};
