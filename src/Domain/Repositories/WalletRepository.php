<?php

namespace PicPay\Domain\Repositories;

use Picpay\Infrastructure\Models\Wallet;

interface WalletRepository
{
    public function getBalance(string $id);

    public function updateBalances(Wallet $payerWallet, Wallet $payeeWallet, $value): void;
}
