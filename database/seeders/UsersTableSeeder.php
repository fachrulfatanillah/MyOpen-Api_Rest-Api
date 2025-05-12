<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'unicode' => (string) Str::uuid(),
                'google_id' => 'google456',
                'name' => 'fachrur fatanillah',
                'email' => 'fachrurfatanillah@example.com',
                'image_url' => 'https://example.com/avatar2.png',
                'status' => 1,
                'level' => 1,
                'created' => now(),
                'updated' => now(),
            ],
            [
                'unicode' => (string) Str::uuid(),
                'google_id' => 'google123',
                'name' => 'Andi Saputra',
                'email' => 'andi@example.com',
                'image_url' => 'https://example.com/avatar1.png',
                'status' => 1,
                'level' => 0,
                'created' => now(),
                'updated' => now(),
            ]
        ]);
    }
}
