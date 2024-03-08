<?php

use Illuminate\Database\Seeder;

class LoanDurationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // check if table users is empty
        if(DB::table('loan_duration_types')->get()->count() == 0){

            DB::table('loan_duration_types')->insert([
                [
                	'id'   	  => 1,
                	'type_en' => 'Daily',
                    'type_kh' => 'ប្រចាំថ្ងៃ',
                    'slug'	  => 'daily',
                    'prefix'  => 'D',
                    'duration_en'  => 'Day',
                    'duration_kh'  => 'ថ្ងៃ',
                ],
                [
                	'id'   	  => 2,
                	'type_en' => 'Weekly',
                    'type_kh' => 'ប្រចាំសប្តាហ៍',
                    'slug'	  => 'weekly',
                    'prefix'  => 'W',
                    'duration_en'  => 'Week',
                    'duration_kh'  => 'សប្តាហ៍',
                ],
                [
                	'id'   	  => 3,
                	'type_en' => '2 weeks',
                    'type_kh' => '2 ស​ប្ដា​ហ៏',
                    'slug'	  => '2weeks',
                    'prefix'  => 'TW',
                    'duration_en'  => 'Week',
                    'duration_kh'  => 'សប្តាហ៍',
                ],
                [
                	'id'   	  => 4,
                	'type_en' => 'Monthly-Card Cri',
                    'type_kh' => 'ប្រចាំខែ',
                    'slug'	  => 'monthly',
                    'prefix'  => 'M',
                    'duration_en'  => 'Month',
                    'duration_kh'  => 'ខែ',
                ],
                [
                	'id'   	  => 5,
                	'type_en' => 'Refinance',
                    'type_kh' => 'ប្រចាំខែ',
                    'slug'	  => 'refinance',
                    'prefix'  => 'R',
                    'duration_en'  => 'Day',
                    'duration_kh'  => 'ថ្ងៃ',
                    'duration_en'  => 'Month',
                    'duration_kh'  => 'ខែ',
                ]
            ]);

        } else { echo "\e[31mTable is not empty, therefore NOT "; }

    }
}
