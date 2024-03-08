<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('date',250);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('add_by',250);
            $table->decimal('price',8,2)->nullable();
            $table->decimal('deposit',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->decimal('interest',8,2)->nullable();
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
        Schema::dropIfExists('sales');
    }
}
