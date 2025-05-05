<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoodTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Senang', 'image_url' => 'public/emojies/smile.jpg'],
            ['name' => 'Sedih', 'image_url' => 'public/emojies/sedih.jpg'],
            ['name' => 'Marah', 'image_url' => 'public/emojies/marah.jpg'],
            ['name' => 'Cemas', 'image_url' => 'public/emojies/cemas.jpg'],
            ['name' => 'Tenang', 'image_url' => 'public/emojies/tenang.jpg'],
            ['name' => 'biasa aja', 'image_url' => 'public/emojies/biasaaja.jpg'],
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

