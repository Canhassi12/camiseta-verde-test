<?php

namespace PicPay\Application\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Picpay\Infrastructure\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Illuminate\Database\Eloquent\Model>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

     public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf' => fake()->numerify('###########'),
            'password' => Hash::make("password123"),
        ];
    }
}
