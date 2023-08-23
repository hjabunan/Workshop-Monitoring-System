<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section = [
            [
                'name' => 'TOYOTA SECTION',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/t-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BT SECTION',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/bt-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'RAYMOND SECTION',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/bt-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'OVERHAULING',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/ovhl-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PPT SECTION',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/ppt-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G - SECTION',
                'status' => '1',
                'isset' => '0',
                'route' => '/dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PDI SECTION',
                'status' => '0',
                'isset' => '0',
                'route' => '/workshop-ms/pdi-monitoring',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CHARGING AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/other-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DELIVERY AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FABRICATION AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/other-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PAINTING AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/other-workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PARKING AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REPAIR AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 1',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 6',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 7',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 8',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 5B',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage5b',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAREHOUSE 5C',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/w-storage5c',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MCI AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/mci-monitoring',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PDI AREA',
                'status' => '1',
                'isset' => '0',
                'route' => '/workshop-ms/pdi-monitoring',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('wms_sections')->insert($section);
    }
}
