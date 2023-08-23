<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dept = [
            [
                'name' => 'IT',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AUDIT',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WORKSHOP',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ADMIN',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TRANSPORT',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SERVICE',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SALES',
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('departments')->insert($dept);
    }
}
