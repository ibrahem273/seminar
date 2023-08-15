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
        Schema::table('subject_time_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->default(1);
            $table->foreign('user_id')->on('users')->cascadeOnDelete();
             });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_time_schedules', function (Blueprint $table) {
            //
        });
    }
};
