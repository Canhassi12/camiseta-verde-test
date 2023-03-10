<?php

namespace PicPay\Application\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Picpay\Infrastructure\Models\User;
use Picpay\Infrastructure\Models\Wallet;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Illuminate\Database\Eloquent\Model>
 */
class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'balance' => 300,
        ];
    }
}
