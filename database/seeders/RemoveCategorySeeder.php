<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class RemoveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->where('name', 'その他')->delete();
        DB::table('categories')->insert([
                'name' => 'ドライブスポット',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '休憩施設',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '大仏・仏像',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => 'イベント',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => 'ファミリー向け施設',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => 'その他',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
    }
}
