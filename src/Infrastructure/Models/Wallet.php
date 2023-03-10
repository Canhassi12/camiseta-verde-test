<?php

namespace PicPay\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PicPay\Application\Factories\WalletFactory;

class Wallet extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'balance',
        'owner_id',
    ];

    protected static function newFactory(): WalletFactory
    {
        return WalletFactory::new();
    }
}
