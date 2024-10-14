<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PlantypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plantypes')->insert([
                'name' => '観光',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'イベント',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => '家族向け',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'カップル向け',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => '食べ歩き',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'アクティビティ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'テーマパーク',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'ゆったり旅行',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'ドライブ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => '海水浴',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('plantypes')->insert([
                'name' => 'その他',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
    }
}
