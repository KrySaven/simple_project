<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->integer('deleted_by')->unsigned()->index()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('user_branches');
    }
}
