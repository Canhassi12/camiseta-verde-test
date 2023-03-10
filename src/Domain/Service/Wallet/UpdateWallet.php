<?php

namespace PicPay\Domain\Service\Wallet;

use PicPay\Domain\Repositories\WalletRepository;
use Picpay\Infrastructure\Models\Wallet;

class UpdateWallet
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    public function handle(string $payerId, string $payeeId, $amount): void
    {
        $this->repository->updateBalances(
            $this->repository->getBalance($payerId),
            $this->repository->getBalance($payeeId),
            $amount
        );
    }
}
