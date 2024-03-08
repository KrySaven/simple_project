<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siteprofiles', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('add_by',50);
            $table->string('site_name',250)->nullable();
            $table->string('company',250)->nullable();
            $table->string('phone',250)->nullable();
            $table->string('email',250)->nullable();
            $table->string('address',250)->nullable();
            $table->string('logo',250)->nullable();
            $table->string('icon',250)->nullable();
            $table->string('facebook',250)->nullable();
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
        Schema::dropIfExists('siteprofiles');
    }
}
