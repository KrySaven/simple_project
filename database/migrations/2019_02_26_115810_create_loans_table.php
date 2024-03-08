<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id',11);
            $table->date('date')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('add_by',250);
            $table->decimal('amount',11,2);
            $table->string('status',10);
            $table->string('description',250)->nullable();
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
        Schema::dropIfExists('loans');
    }
}
