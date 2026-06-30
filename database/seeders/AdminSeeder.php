<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // prevent duplicate seeding
            [
                'role_id' => 1,
                'name' => 'Admin',
                'refer' => 0,
                'username' => 'admin',
                'phone' => '01749699156',
                'password' => Hash::make('password'),
                'oauth_id' => null,
                'oauth_type' => null,
                'is_approved' => true,
                'status' => true,
                'cancel_attempt' => 0,
                'avatar' => 'default.png',
                'point' => 0,
                'pen_point' => null,
                'joining_date' => now()->toDateString(),
                'joining_month' => now()->format('F'),
                'joining_year' => now()->year,
                'email_verified_at' => now(),
                'desig' => null,
                'wallate' => 0,
                'remember_token' => Str::random(10),
            ]
        );
    }
}
