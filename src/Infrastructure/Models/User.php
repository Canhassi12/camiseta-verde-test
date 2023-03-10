<?php

namespace PicPay\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PicPay\Application\Factories\UserFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class,'owner_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
