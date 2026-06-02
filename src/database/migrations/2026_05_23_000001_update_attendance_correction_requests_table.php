<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateAttendanceCorrectionRequestsTable extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_correction_requests', function (Blueprint $table) {
            $table->date('requested_work_date')->nullable()->after('user_id');
        });

        DB::statement('ALTER TABLE attendance_correction_requests MODIFY attendance_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE attendance_correction_requests MODIFY attendance_id BIGINT UNSIGNED NOT NULL');

        Schema::table('attendance_correction_requests', function (Blueprint $table) {
            $table->dropColumn('requested_work_date');
        });
    }
}
