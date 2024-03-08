<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnToBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Branches', function (Blueprint $table) {
            $table->string('owner_title_en')->nullable()->after('owner_name_kh');
            $table->string('owner_title_kh')->nullable()->after('owner_title_en');
            $table->string('sex')->nullable()->after('owner_title_kh');
            $table->date('date_of_birth')->nullable()->after('sex');
            $table->string('nationality')->nullable()->after('date_of_birth');
            $table->string('identity_number')->nullable()->after('nationality');
            $table->date('identity_created_at')->nullable()->after('identity_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Branches', function (Blueprint $table) {
            //
        });
    }
}
