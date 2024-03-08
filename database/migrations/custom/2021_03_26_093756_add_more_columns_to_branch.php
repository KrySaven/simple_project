<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Branches', function (Blueprint $table) {
            $table->tinyInteger('schedule_excluding_public_holiday')->nullable()->default('0');
            $table->tinyInteger('schedule_excluding_saturday')->nullable()->default('0');
            $table->tinyInteger('schedule_excluding_sunday')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Branchs', function (Blueprint $table) {
            //
        });
    }
}
