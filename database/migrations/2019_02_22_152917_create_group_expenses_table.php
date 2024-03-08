<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_expenses', function (Blueprint $table) {
            //
            $table->increments('id', 11);
            $table->string('expense_name',250);
            $table->string('description',250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_expenses', function (Blueprint $table) {
            //
        });
    }
}
