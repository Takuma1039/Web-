<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spots')->insert([
                'name' => '国営ひたち海浜公',
                'body' => '花と緑に囲まれた国営ひたち海浜公園。開園面積約215haの広い園内は7つのエリアに分かれており、自然の中で楽しめるレジャースポットや花畑があります。',
                'address' => '茨城県ひたちなか市馬渡字大沼605-4',
                'lat' => '36.4059',
                'long' => '140.5965',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'deleted_at' => NULL,
         ]);
    }
}
