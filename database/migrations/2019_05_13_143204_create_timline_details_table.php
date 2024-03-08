<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimlineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timline_details', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('duration_type',10)->nullable();
            $table->decimal('percentage',8,2)->nullable();
            $table->decimal('duration',8,2)->nullable();
            $table->string('description',250)->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('creator_id')->unsigned()->index()->nullable();
            $table->integer('updater_id')->unsigned()->index()->nullable();
            $table->integer('deleter_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timline_details');
    }
}
