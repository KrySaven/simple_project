<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('name_kh')->nullable();
            $table->string('owner_name_en')->nullable();
            $table->string('owner_name_kh')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('map')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('facebook')->nullable();
            $table->string('line')->nullable();
            $table->dropColumn('branch_name');
            $table->dropColumn('creator_id');
            $table->dropColumn('updater_id');
            $table->dropColumn('deleter_id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->integer('deleted_by')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
}
