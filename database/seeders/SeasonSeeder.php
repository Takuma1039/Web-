<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seasons')->insert([
                'name' => '春',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('seasons')->insert([
                'name' => '夏',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('seasons')->insert([
                'name' => '秋',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('seasons')->insert([
                'name' => '冬',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
    }
}
