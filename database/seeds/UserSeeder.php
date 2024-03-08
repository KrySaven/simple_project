<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        if(DB::table('users')->get()->count()==0){
        	DB::table('users')->insert([
        		[
        			'id'	 	=> 1,
        			'group_id'	=> 1,
        			'name' 		=> 'Super Admin',
        			'name_kh' 	=> 'រដ្ឋបាលជាន់ខ្ពស់',
        			'email'	  	=> 'superadmin@loan.com',
        			'password'	=> bcrypt('SuperAdminpwd#123'),
        			'profile'   => 'images/user.png',
        			'is_active' => 1
        		],
        	]);
        }else{
        	echo "The Table is not empty";
        }
    }
}
