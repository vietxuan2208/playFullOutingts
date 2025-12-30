<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('username', 'admin')->first();
        if (!$admin) {
            User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('secret123'),
                'role_id' => 2,
                'status' => 1,
                'is_delete' => 0,
            ]);
        }
    }
}
