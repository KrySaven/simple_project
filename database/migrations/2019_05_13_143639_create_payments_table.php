<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('payment_date',250);
            $table->string('actual_date',250);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('add_by',250);
            $table->decimal('amount',8,2)->nullable();
            $table->decimal('interest',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->decimal('percentage',8,2)->nullable();
            $table->string('description',250)->nullable();
            $table->string('status',10)->nullable();
            $table->tinyInteger('active')->default('1');
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
        Schema::dropIfExists('payments');
    }
}
