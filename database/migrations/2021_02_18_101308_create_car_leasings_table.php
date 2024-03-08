<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarLeasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_leasings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('color',250)->nullable();
            $table->string('make_model',250)->nullable();
            $table->string('tax_stamp',250)->nullable();
            $table->string('vin',250)->nullable();
            $table->string('cylineder_size',250)->nullable();
            $table->date('year',250);
            $table->date('first_card_issuace_date',250);
            $table->decimal('market_price',8,2);
            $table->decimal('hot_price',8,2);
            $table->decimal('pawn_amount',8,2);
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
        Schema::dropIfExists('car_leasings');
    }
}
