<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth', 20)->nullable();
            $table->string('identity_type', 20)->nullable();
            $table->string('identity_number', 100)->nullable();
            $table->string('identity_card_create_date', 50)->nullable();
            $table->string('issue_by', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('education_level', 50)->nullable();
            $table->string('family_status', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('house_no', 50)->nullable();
            $table->string('street_no', 50)->nullable();
            $table->string('address_group', 50)->nullable();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('district_id')->unsigned()->nullable();
            $table->integer('commune_id')->unsigned()->nullable();
            $table->integer('village_id')->unsigned()->nullable();
            $table->string('facebook_name', 50)->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('description')->nullable();
            $table->string('business_occupation')->nullable();
            $table->string('business_term')->nullable();
            $table->string('business_house_no')->nullable();
            $table->string('business_street_no')->nullable();
            $table->string('business_group')->nullable();
            $table->integer('business_province_id')->unsigned()->nullable();
            $table->integer('business_district_id')->unsigned()->nullable();
            $table->integer('business_commune_id')->unsigned()->nullable();
            $table->integer('business_village_id')->unsigned()->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
