<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_table_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('section');
            $table->foreignId('session_year_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('class_room_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('start_day');
            $table->string('start_minute');
            $table->string('start_hour');
            $table->string('end_minute');
            $table->string('end_hour');
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
        Schema::dropIfExists('class_routines');
    }
}
