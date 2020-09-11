<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('designation');
            $table->string('department');
            $table->string('phone');
            $table->string('gender');
            $table->string('blood');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('linkedin');
            $table->string('address');
            $table->text('about');
            $table->text('image');
            $table->tinyInteger('show_web')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
