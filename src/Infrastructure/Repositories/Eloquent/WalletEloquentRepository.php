<?php

namespace PicPay\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PicPay\Domain\Repositories\WalletRepository;
use Picpay\Infrastructure\Models\Wallet;

class WalletEloquentRepository implements WalletRepository
{

    public function getBalance(string $id): Model|Builder
    {
        return Wallet::query()->lockForUpdate()->where('owner_id', $id)->firstOrFail();
    }

    public function updateBalances(Wallet $payerWallet, Wallet $payeeWallet, $value): void
    {
        $payerWallet->update(['balance' => $payerWallet->balance - $value]);

        $payeeWallet->update(['balance' => $payeeWallet->balance + $value]);
    }
}
