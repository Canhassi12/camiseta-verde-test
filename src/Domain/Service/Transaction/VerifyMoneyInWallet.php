<?php

namespace PicPay\Domain\Service\Transaction;

use PicPay\Domain\Exception\TransactionException;
use PicPay\Domain\Repositories\WalletRepository;

class VerifyMoneyInWallet
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    /**
     * @throws TransactionException
     */
    public function execute(array $data): void
    {
        $balance = $this->repository->getBalance($data['payer_id']);

        if ($balance['balance'] < $data['amount']) {
            throw TransactionException::insufficientFunds();
        }
    }
}
