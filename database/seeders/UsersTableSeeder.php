<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wms_users')->insert([
            'name' => 'Super Admin',
            'email' => 'it04@toyotaforklifts-philippines.com',
            'idnum' => 'admin',
            'email_verified_at' => now(),
            'dept' => '1',
            'area' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21',
            'password' => Hash::make('super@dm!n'),
            'role' => '1',
            'status' => 1,
            'remember_token' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
