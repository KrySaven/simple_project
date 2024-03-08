<?php

use Illuminate\Database\Seeder;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // check if table users is empty
        if(DB::table('relations')->get()->count() == 0){

            DB::table('relations')->insert([
                [
                	'id'   	  => 1,
                	'name_en' => 'Parents',
                    'name_kh' => 'ឪពុកម្តាយ',
                    'slug'    => 'parent'
                ],
                [
                	'id'   	  => 2,
                	'name_en' => 'Sibling',
                    'name_kh' => 'បងប្អូនបង្កើត',
                    'slug'    => 'sibling'
                ],
                [
                	'id'   	  => 3,
                	'name_en' => 'Couple',
                    'name_kh' => 'ប្តីប្រពន្ធ',
                	'slug'    => 'couple'
                ],
                [
                	'id'   	  => 4,
                	'name_en' => 'Friends',
                    'name_kh' => 'មិត្តភក្តិ',
                    'slug'    => 'friends',
                ],
                [
                	'id'   	  => 5,
                	'name_en' => 'Other',
                    'name_kh' => 'ផ្សេងទៀត',
                    'slug'    => 'other',
                ],
            ]);

        } else { echo "\e[31mTable is not empty, therefore NOT "; }

    }
}
