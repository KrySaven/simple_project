<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnToGuarantors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guarantors', function (Blueprint $table) {
            $table->string('identity_type')->after('id');
            $table->string('guarantor_relation_name_en')->after('identity_type')->nullable();
            $table->string('guarantor_relation_name_kh')->after('identity_type')->nullable();
            $table->string('guarantor_relation_sex')->after('identity_type')->nullable();
            $table->date('guarantor_relation_date_of_birth')->after('identity_type')->nullable();
            $table->date('guarantor_relation_identity_created_at')->after('identity_type')->nullable();
            $table->string('guarantor_relation_identity_type')->after('identity_type')->nullable();
            $table->string('guarantor_relation_identity_number')->after('identity_type')->nullable();
            $table->string('guarantor_relation')->after('identity_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guarantors', function (Blueprint $table) {
            //
        });
    }
}
