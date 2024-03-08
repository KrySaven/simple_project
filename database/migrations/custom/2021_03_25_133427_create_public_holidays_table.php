<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id');
            $table->string('name_en');
            $table->string('name_kh')->nullable();
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->string('note')->nullable();;
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->integer('deleted_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('public_holidays');
    }
}
