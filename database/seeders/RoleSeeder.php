<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'user'],
            ['id' => 2, 'name' => 'admin'],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['id' => $r['id']], ['name' => $r['name']]);
        }
    }
}
