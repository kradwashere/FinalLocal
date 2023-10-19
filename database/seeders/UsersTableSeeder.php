<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'administrator',
                'email' => 'kradjumli@gmail.com',
                'email_verified_at' => '2023-10-10 18:07:59',
                'password' => '$2y$10$7pDX86PfKiwWvdBR1tYVbeX8vzGwEsXmvqaFwU4BwNPfcsoPS/Mtq',
                'temp_password' => NULL,
                'avatar' => 'administrator.png',
                'role' => 'Administrator',
                'is_active' => 1,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-10 18:07:59',
                'updated_at' => '2023-10-10 18:11:57',
            ),
            1 => 
            array (
                'id' => 2,
                'username' => 'jbn0806',
                'email' => 'jpine652001@yahoo.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$fxwCDHwtjHA5Gi8UpniN6.z/26TnqJVKSGzh1PS18/93Uiz3iVpMC',
                'temp_password' => '9852815741',
                'avatar' => 'jbn0806.png',
                'role' => 'Scholarship Coordinator',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 10:58:24',
                'updated_at' => '2023-10-14 10:58:24',
            ),
            2 => 
            array (
                'id' => 3,
                'username' => 'zfa0303',
                'email' => 'zorenantonio@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$UKr2YE0Pp/GFfAr.3lSGn.2UJoMgL.tjQrFXKCbk.opefidTPVR7m',
                'temp_password' => '6227459513',
                'avatar' => 'zfa0303.png',
                'role' => 'Scholarship Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:01:00',
                'updated_at' => '2023-10-14 11:01:00',
            ),
            3 => 
            array (
                'id' => 4,
                'username' => 'ssb1121',
                'email' => 'bernardosheila21@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$vMJyDCfD5TnhGIZyOf/QKeK2Tw8pP0v4ViJ4Es//ox8igsdNR5MnK',
                'temp_password' => '8596762682',
                'avatar' => 'ssb1121.png',
                'role' => 'Scholarship Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:02:08',
                'updated_at' => '2023-10-14 11:06:04',
            ),
            4 => 
            array (
                'id' => 5,
                'username' => 'sva0308',
                'email' => 'stenel_rizza@yahoo.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Mf7LKVP3ef9SJX3VymqO2OE0JUfstsfjyHIIxLF4WFmzTC.LmkyZu',
                'temp_password' => '1348619644',
                'avatar' => 'sva0308.png',
                'role' => 'Scholarship Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:03:13',
                'updated_at' => '2023-10-14 11:06:09',
            ),
            5 => 
            array (
                'id' => 6,
                'username' => 'mgc0312',
                'email' => 'cachinmarc@yahoo.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$8HWbLmd69KqJXA.6WmrwH.jE/2HeqYi0KsgPqolyy8Ob4Q9agulUy',
                'temp_password' => '5625963182',
                'avatar' => 'mgc0312.jpeg',
                'role' => 'PSTO Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:26:56',
                'updated_at' => '2023-10-14 11:26:56',
            ),
            6 => 
            array (
                'id' => 7,
                'username' => 'sfj0919',
                'email' => 'jovenalsheryl@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$LEZDJ1bEVjeJeYAtFxZ5teW6YNwfPb8ECI0MC0YMj07FYzv1t1NeG',
                'temp_password' => '4349209920',
                'avatar' => 'sfj0919.jpeg',
                'role' => 'PSTO Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:28:04',
                'updated_at' => '2023-10-14 11:28:04',
            ),
            7 => 
            array (
                'id' => 8,
                'username' => 'jpa1101',
                'email' => 'jeyzel_aparri@yahoo.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$BTYYBijj5riQjQ7X2kyND.bzCFLvNVQ2D.s/LPmi.0wFjxsCKYeYm',
                'temp_password' => '1551868874',
                'avatar' => 'jpa1101.jpeg',
                'role' => 'PSTO Staff',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-10-14 11:28:57',
                'updated_at' => '2023-10-14 11:28:57',
            ),
        ));
        
        
    }
}