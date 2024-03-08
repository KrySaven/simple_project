<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuarantorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->increments('id',11);
            $table->string('type',20)->nullable();
            $table->string('name_kh',250)->nullable();
            $table->string('name',250)->nullable();
            $table->string('gender',10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone',250)->nullable();
            $table->string('company',250)->nullable();
            $table->string('email',250)->nullable();
            $table->string('identity_number',25)->nullable();
            $table->string('issued_by',250)->nullable();
            $table->string('nationality',100)->nullable();
            $table->string('family_status',50)->nullable();
            $table->string('education_level',50)->nullable();
            $table->string('education_level_other',150)->nullable();
            $table->string('address',250)->nullable();
            $table->string('description',250)->nullable();
            $table->string('url',250)->nullable();
            $table->string('identity',250)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('house_no',20)->nullable();
            $table->string('street_no',20)->nullable();
            $table->string('add_group',20)->nullable();

            $table->integer('province_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('commune_id')->nullable();
            $table->integer('village_id')->nullable();
            $table->string('personal_ownership',100)->nullable();
            $table->string('facebook_name',100)->nullable();
            $table->string('facebook_link',250)->nullable();
            $table->string('work_company',200)->nullable();
            $table->string('work_role',120)->nullable();
            $table->decimal('work_salary',8,2)->default(0);

            $table->string('work_house_no',20)->nullable();
            $table->string('work_street_no',50)->nullable();
            $table->string('work_group',100)->nullable();
            $table->integer('work_province_id')->nullable();
            $table->integer('work_district_id')->nullable();
            $table->integer('work_commune_id')->nullable();
            $table->integer('work_village_id')->nullable();

            $table->string('business_occupation',250)->nullable();
            $table->string('business_term',250)->nullable();
            $table->string('business_house_no',50)->nullable();
            $table->string('business_street_no',50)->nullable();
            $table->string('business_group',150)->nullable();
            $table->integer('business_province_id')->nullable();
            $table->integer('business_district_id')->nullable();
            $table->integer('business_commune_id')->nullable();
            $table->integer('business_village_id')->nullable();

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
        Schema::dropIfExists('guarantors');
    }
}
