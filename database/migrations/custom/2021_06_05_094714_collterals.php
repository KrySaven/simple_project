<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Collterals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaterals', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->integer('customer_id');
            $table->string('number');
            $table->string('value')->nullable();
            $table->string('address')->nullable();
            $table->string('note')->nullable();
            $table->string('photo')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('loan_id')->references('id')->on('sales')->onDelete('cascade');
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
        Schema::dropIfExists('collaterals');
    }
}
