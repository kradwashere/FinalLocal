<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserProfilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_profiles')->delete();
        
        \DB::table('user_profiles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Ra-ouf',
                'lastname' => 'Jumli',
                'middlename' => 'Indanan',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09171531652',
                'profileable_type' => 'App\\Models\\LocationRegion',
                'profileable_id' => 90000000,
                'user_id' => 1,
                'created_at' => '2023-10-10 18:07:59',
                'updated_at' => '2023-10-10 18:07:59',
            ),
            1 => 
            array (
                'id' => 2,
                'firstname' => 'Josephine',
                'lastname' => 'Nohay',
                'middlename' => 'Behoc',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09171208213',
                'profileable_type' => 'App\\Models\\LocationRegion',
                'profileable_id' => 90000000,
                'user_id' => 2,
                'created_at' => '2023-10-14 10:58:24',
                'updated_at' => '2023-10-14 10:58:24',
            ),
            2 => 
            array (
                'id' => 3,
                'firstname' => 'Zoren',
                'lastname' => 'Antonio',
                'middlename' => 'Fabile',
                'suffix' => NULL,
                'gender' => 'M',
                'mobile' => '09958418776',
                'profileable_type' => 'App\\Models\\LocationRegion',
                'profileable_id' => 90000000,
                'user_id' => 3,
                'created_at' => '2023-10-14 11:01:00',
                'updated_at' => '2023-10-14 11:01:00',
            ),
            3 => 
            array (
                'id' => 4,
                'firstname' => 'Sheila',
                'lastname' => 'Bernardo',
                'middlename' => 'Salenga',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09060775053',
                'profileable_type' => 'App\\Models\\LocationRegion',
                'profileable_id' => 90000000,
                'user_id' => 4,
                'created_at' => '2023-10-14 11:02:08',
                'updated_at' => '2023-10-14 11:02:08',
            ),
            4 => 
            array (
                'id' => 5,
                'firstname' => 'Stenel Rizza',
                'lastname' => 'Alegre',
                'middlename' => 'Villanueva',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09156383457',
                'profileable_type' => 'App\\Models\\LocationRegion',
                'profileable_id' => 90000000,
                'user_id' => 5,
                'created_at' => '2023-10-14 11:03:13',
                'updated_at' => '2023-10-14 11:03:13',
            ),
            5 => 
            array (
                'id' => 6,
                'firstname' => 'Marc',
                'lastname' => 'Cachin',
                'middlename' => 'Guimbarda',
                'suffix' => NULL,
                'gender' => 'M',
                'mobile' => '09773325701',
                'profileable_type' => 'App\\Models\\LocationProvince',
                'profileable_id' => 97200000,
                'user_id' => 6,
                'created_at' => '2023-10-14 11:26:56',
                'updated_at' => '2023-10-14 11:26:56',
            ),
            6 => 
            array (
                'id' => 7,
                'firstname' => 'Sheryl',
                'lastname' => 'Jovenal',
                'middlename' => 'Flores',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09778205540',
                'profileable_type' => 'App\\Models\\LocationProvince',
                'profileable_id' => 97300000,
                'user_id' => 7,
                'created_at' => '2023-10-14 11:28:04',
                'updated_at' => '2023-10-14 11:28:04',
            ),
            7 => 
            array (
                'id' => 8,
                'firstname' => 'Jeyzel',
                'lastname' => 'Aparri',
                'middlename' => 'Perez',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09778202436',
                'profileable_type' => 'App\\Models\\LocationProvince',
                'profileable_id' => 98300000,
                'user_id' => 8,
                'created_at' => '2023-10-14 11:28:57',
                'updated_at' => '2023-10-14 11:28:57',
            ),
        ));
        
        
    }
}