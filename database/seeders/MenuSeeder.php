<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'name' => 'Mie Gacoan Level 1',
                'description' => 'Mie pedas level 1 untuk pemula.',
                'price' => 12000,
                'image' => 'mie_gacoan_level_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mie Iblis',
                'description' => 'Mie super pedas khas Gacoan.',
                'price' => 15000,
                'image' => 'mie_iblis.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Es Genderuwo',
                'description' => 'Minuman segar rasa leci dengan jelly.',
                'price' => 8000,
                'image' => 'es_genderuwo.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
            
                'name' => 'Mie Tuyul',
                'description' => 'Mie gurih dengan tingkat kepedasan ringan, favorit anak-anak.',
                'price' => 13000,
                'image' => 'mie-tuyul.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],

            [

                'name' => 'Es Pocong',
                'description' => 'Minuman susu stroberi dingin dengan topping jelly.',
                'price' => 9000,
                'image' => 'es-pocong.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],

            [

                'name' => 'Dimsum Hantu',
                'description' => 'Dimsum kukus isi ayam dengan saus spesial yang dimasak dengan matang.',
                'price' => 11000,
                'image' => 'dimsum-hantu.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ], 
        ]);
    }
}
