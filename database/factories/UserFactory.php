<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'role_id' => Role::where('slug', 'user')->value('id') ?? 3,
            'name' => fake()->name(),
            'refer' => 0,
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('017########'),
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
        ];
    }
}
