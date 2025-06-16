<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Maneger',
            'role_id' => '1',
            'username' => 'admin',
            'age' => '45',
            'password' => 'admin123',
        ]);
    }
}
