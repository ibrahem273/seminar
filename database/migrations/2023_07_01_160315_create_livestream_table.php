<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livestream', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('imageUrl')->default('https://thumbs.dreamstime.com/b/live-stream-logo-design-vector-illustration-design-template-live-stream-logo-design-vector-illustration-161152543.jpg');
//            $table->unsignedBigInteger('broadcaster_id')->nullable();
//            $table->foreign('broadcaster_id')->references('id')->on('users')->cascadeOnDelete();
//            $table->foreignId('broadcaster_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('startedAt')->default(\Carbon\Carbon::now());
            $table->integer('viewers_count')->default(0);
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
        Schema::dropIfExists('table_livestream');
    }
};
