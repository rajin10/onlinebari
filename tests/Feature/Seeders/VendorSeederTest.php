<?php

use App\Models\Role;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\VendorAccount;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(fn () => $this->seed(RoleSeeder::class));

it('seeds vendors with shop and account', function () {
    $this->seed(VendorSeeder::class);

    $vendorRoleId = Role::where('slug', 'vendor')->value('id');
    expect(User::where('role_id', $vendorRoleId)->count())->toBe(4);
    expect(ShopInfo::count())->toBe(4);
    expect(VendorAccount::count())->toBe(4);

    $vendor = User::where('role_id', $vendorRoleId)->first();
    expect($vendor->shop_info)->not->toBeNull();
});

it('VendorSeeder is idempotent', function () {
    $this->seed(VendorSeeder::class);
    $this->seed(VendorSeeder::class);

    expect(ShopInfo::count())->toBe(4);
});
