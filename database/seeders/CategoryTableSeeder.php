<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $cat = [
            [
                'cat_name' => 'VACANT',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_name' => 'OCCUPIED',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cat_name' => 'CAUTION',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('wms_categories')->insert($cat);
    }
}
