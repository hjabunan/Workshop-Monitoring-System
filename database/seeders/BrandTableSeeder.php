<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brand = [
            [
                'name' => 'TOYOTA',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BT',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'RAYMOND',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('brands')->insert($brand);
    }
}
