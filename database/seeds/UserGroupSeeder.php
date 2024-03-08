<?php

use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        if(DB::table('user_groups')->get()->count()==0){
        	DB::table('user_groups')->insert([
        		[
        			'id'	 	=> 1,
        			'group_name'=> 'Super Admin'
        		],
        	]);
        }else{
        	echo "The Table is not empty";
        }
    }
}
