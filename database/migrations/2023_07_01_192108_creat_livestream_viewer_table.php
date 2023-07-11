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
        Schema::create('livestream_viewer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestream_id')->constrained('livestream')->cascadeOnDelete();
//            $table->unsignedBigInteger('viewer_id');
//            $table->foreign('viewer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('viewer_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livestream_viewer');
    }

};
