<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locals')->insert([
                'name' => '群馬',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '栃木',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '茨城',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '埼玉',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '東京',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '千葉',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('locals')->insert([
                'name' => '神奈川',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
    }
}
