<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payoffs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('loan_id');
            $table->date('date');
            $table->decimal('principle',18,2);
            $table->decimal('interest',18,2);
            $table->decimal('insurance',18,2);
            $table->decimal('admin_fee',18,2);
            $table->decimal('penalty',18,2)->nullable();
            $table->string('pay_off_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payoffs');
    }
}
