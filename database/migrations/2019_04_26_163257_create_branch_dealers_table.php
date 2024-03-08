<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_dealers', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('dealer_id')->unsigned()->nullable();
            $table->string('name',250)->nullable();
            $table->string('long',250)->nullable();
            $table->string('late',250)->nullable();
            $table->string('address',250)->nullable();
            $table->string('description',250)->nullable();
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
        Schema::dropIfExists('branch_dealers');
    }
}
