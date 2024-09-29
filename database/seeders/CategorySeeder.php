<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
                'name' => '遊園地・テーマパーク',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '動物園',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '水族館',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '博物館・美術館',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '温泉・銭湯',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '神社・お寺',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '自然',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '城・遺跡',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '公園',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '牧場・農園',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '歴史的建造物',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '展望台・タワー',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '教会',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '橋・ダム',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => 'お花見',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '動物園',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '遊覧船・船',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => 'レジャー・アウトドア施設',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
        ]);
        DB::table('categories')->insert([
                'name' => '海水浴場',
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
