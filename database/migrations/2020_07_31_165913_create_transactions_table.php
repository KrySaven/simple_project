<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id',11);
            $table->date('date')->nullable();
            $table->decimal('amount_usd',11,3);
            $table->decimal('amount_riel',18,3);
            $table->decimal('exchange',8,2);

            $table->integer('open_balance_id')->unsigned()->nullable();
            $table->integer('close_balance_id')->unsigned()->nullable();

            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->integer('journal_id')->unsigned()->nullable();
            $table->integer('payroll_id')->unsigned()->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->integer('invoice_pay_id')->unsigned()->nullable();
            
            $table->integer('expen_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('is_close')->unsigned()->nullable();
            $table->string('status',250);
            $table->string('description',250);
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
        Schema::dropIfExists('transactions');
    }
}
