<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoodTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Senang', 'image_url' => 'emojies/smile.jpg'],
            ['name' => 'Sedih', 'image_url' => 'emojies/sedih.jpg'],
            ['name' => 'Marah', 'image_url' => 'emojies/marah.jpg'],
            ['name' => 'Cemas', 'image_url' => 'emojies/cemas.jpg'],
            ['name' => 'Tenang', 'image_url' => 'emojies/tenang.jpg'],
            ['name' => 'biasa aja', 'image_url' => 'emojies/biasaaja.jpg'],
            ['name' => 'malas', 'image_url' => 'emojies/malas.jpg'],
            ['name' => 'malu', 'image_url' => 'emojies/malu.jpg'],
            ['name' => 'suka', 'image_url' => 'emojies/suka.jpg'],
        ];

        foreach ($types as $type) {
            DB::table('mood_types')->insert([
                'name' => $type['name'],
                'image_url' => $type['image_url'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

