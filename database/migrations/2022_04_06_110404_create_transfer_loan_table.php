<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_loan', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('cus_id')->unsigned()->nullable();
            $table->integer('old_co_id')->unsigned()->nullable();
            $table->decimal('paid_principle',8,2)->nullable();
            $table->decimal('paid_insterest',8,2)->nullable();
            $table->decimal('balance',8,2)->nullable();
            $table->string('transfer_date',250);
            $table->string('description',250);
            $table->string('transfer_by',250);
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
        Schema::dropIfExists('transfer_loan');
    }
}
