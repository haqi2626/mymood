<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avatar;

class AvatarSeeder extends Seeder
{
    public function run()
    {
        $avatars = [
            ['avatar_path' => 'avatars/avatar1.jpg'],
            ['avatar_path' => 'avatars/avatar2.jpg'],
            ['avatar_path' => 'avatars/avatar3.jpg'],
            ['avatar_path' => 'avatars/avatar4.jpg'],
            ['avatar_path' => 'avatars/avatar5.jpg'],
            ['avatar_path' => 'avatars/avatar6.jpg'],
            ['avatar_path' => 'avatars/avatar7.jpg'],
        ];

        foreach ($avatars as $avatar) {
            Avatar::create($avatar);
        }
    }
}
