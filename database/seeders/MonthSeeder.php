<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('months')->insert([
                'name' => '1月～2月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '2月～3月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '3月～4月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '4月～5月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '5月～6月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '6月～7月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '7月～8月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '8月～9月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '9月～10月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '10月～11月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '11月～12月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '1月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '2月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '3月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '4月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '5月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '6月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '7月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '8月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '9月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '10月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '11月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
        DB::table('months')->insert([
                'name' => '12月',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
    }
}
