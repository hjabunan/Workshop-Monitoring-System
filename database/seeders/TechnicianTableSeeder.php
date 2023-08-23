<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnicianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tech = [
            [
                'name' => 'HENRY P JABUNAN',
                'initials' => 'HPJ',
                'section' => 'TOYOTA SECTION',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'name' => 'GHIO V MADRID',
            //     'initials' => 'GVM',
            //     'section' => 'BT SECTION',
            //     'status' => '1',
            //     'is_deleted' => '0',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ];
        
        DB::table('wms_technicians')->insert($tech);
    }
}
