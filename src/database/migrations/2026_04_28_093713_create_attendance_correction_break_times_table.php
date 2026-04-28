<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceCorrectionBreakTimesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_correction_break_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_correction_request_id');
            $table->dateTime('break_start_at')->nullable();
            $table->dateTime('break_end_at')->nullable();
            $table->timestamps();

            $table->foreign(
                'attendance_correction_request_id',
                'acbt_request_id_fk'
            )->references('id')
                ->on('attendance_correction_requests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_correction_break_times');
    }
}
