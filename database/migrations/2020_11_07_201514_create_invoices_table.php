<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_year_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('class_table_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->double('amount',14,2);
            $table->double('paidAmount',14,2);
            $table->tinyInteger('status');
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
        Schema::dropIfExists('invoices');
    }
}
