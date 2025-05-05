<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            ['name' => 'Keluarga', 'color' => '#F87171'],
            ['name' => 'Teman', 'color' => '#60A5FA'],
            ['name' => 'Pekerjaan', 'color' => '#34D399'],
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->insert([
                'name' => $tag['name'],
                'user_id' => 1, // Ganti dengan user_id yang sesuai
                'color' => $tag['color'],
            ]);
        }
    }
}

