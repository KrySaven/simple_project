<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollateralDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collateral_details', function (Blueprint $table) {
            $table->id();
            $table->integer('collateral_id');
            $table->string('collateral_type');
            $table->string('collateral_name');
            $table->string('color');
            $table->string('licence_type');
            $table->year('year_of_mfg');
            $table->string('engine_no');
            $table->string('frame_no');
            $table->date('first_date_registeration');
            $table->string('file');
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
        Schema::dropIfExists('collateral_details');
    }
}
