<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDurationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_duration_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_kh');
            $table->string('type_en');
            $table->string('prefix');
            $table->string('slug');
            $table->string('duration_en');
            $table->string('duration_kh');
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
        Schema::dropIfExists('loan_duration_types');
    }
}
