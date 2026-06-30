<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(fn () => $this->seed(RoleSeeder::class));

it('seeds 25 customers with the user role', function () {
    $this->seed(CustomerSeeder::class);

    $userRoleId = Role::where('slug', 'user')->value('id');
    expect(User::where('role_id', $userRoleId)->count())->toBe(25);
});

it('CustomerSeeder is idempotent', function () {
    $this->seed(CustomerSeeder::class);
    $this->seed(CustomerSeeder::class);

    $userRoleId = Role::where('slug', 'user')->value('id');
    expect(User::where('role_id', $userRoleId)->count())->toBe(25);
});
