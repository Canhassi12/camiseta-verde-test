<?php

namespace PicPay\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Outbox extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'transaction_id',
        'type',
        'payload',
        'attempts',
    ];
}
