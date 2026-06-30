<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;

        if (User::where('role_id', $userRoleId)->count() >= 25) {
            return;
        }

        User::factory()->count(25)->create(['role_id' => $userRoleId]);
    }
}
