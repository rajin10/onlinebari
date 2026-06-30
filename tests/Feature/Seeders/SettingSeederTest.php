<?php

use App\Models\Role;
use App\Models\Setting;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingSeeder;

it('seeds logo and currency settings', function () {
    $this->seed(SettingSeeder::class);

    expect(Setting::where('name', 'logo')->value('value'))->toBe('logo.svg');
    expect(Setting::where('name', 'CURRENCY_CODE_MIN')->value('value'))->not->toBeNull();
    expect(Setting::where('name', 'TOP_HEADER_STYLE')->value('value'))->toBe('1');
});

it('SettingSeeder is idempotent', function () {
    $this->seed(SettingSeeder::class);
    $this->seed(SettingSeeder::class);

    expect(Setting::where('name', 'logo')->count())->toBe(1);
});

it('RoleSeeder is idempotent', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(Role::count())->toBe(3);
});
