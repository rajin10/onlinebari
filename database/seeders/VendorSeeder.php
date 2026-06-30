<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\VendorAccount;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        $vendorRoleId = Role::where('slug', 'vendor')->value('id') ?? 2;

        $shops = [
            ['shop' => 'Cozy Lighting', 'owner' => 'Tasnim Ahmed'],
            ['shop' => 'Aurora Studio', 'owner' => 'Rafiq Hasan'],
            ['shop' => 'Lumen House', 'owner' => 'Nadia Karim'],
            ['shop' => 'Glow Craft', 'owner' => 'Imran Sheikh'],
        ];

        foreach ($shops as $i => $shop) {
            $username = 'vendor'.($i + 1);

            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'role_id' => $vendorRoleId,
                    'name' => $shop['owner'],
                    'refer' => 0,
                    'email' => $username.'@example.com',
                    'phone' => '0171000000'.($i + 1),
                    'password' => Hash::make('password'),
                    'is_approved' => true,
                    'status' => true,
                    'cancel_attempt' => 0,
                    'avatar' => 'default.png',
                    'point' => 0,
                    'joining_date' => now()->toDateString(),
                    'joining_month' => now()->format('F'),
                    'joining_year' => now()->year,
                    'email_verified_at' => now(),
                    'wallate' => 0,
                    'remember_token' => Str::random(10),
                ]
            );

            ShopInfo::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'is_admin' => 0,
                    'name' => $shop['shop'],
                    'gmail' => $username.'@example.com',
                    'slug' => Str::slug($shop['shop']),
                    'address' => 'Dhaka, Bangladesh',
                    'profile' => $this->pickImage('category'),
                    'cover_photo' => $this->pickImage('banner'),
                    'description' => 'Handcrafted decorative lighting from '.$shop['shop'].'.',
                ]
            );

            VendorAccount::firstOrCreate(['vendor_id' => $user->id], ['amount' => 0]);
        }
    }
}
