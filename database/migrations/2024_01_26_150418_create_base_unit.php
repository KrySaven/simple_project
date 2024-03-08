<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_unit', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('description_kh', 255)->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->integer('deleted_by')->unsigned()->index()->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('base_unit');
    }
}
