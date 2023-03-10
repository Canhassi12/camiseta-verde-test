<?php

namespace PicPay\Domain\Service\Transaction;

use PicPay\Domain\Exception\TransactionException;
use PicPay\Domain\Repositories\WalletRepository;

class IsNotDifferentUser
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    /*
     * @throws TransactionException
     */
    /**
     * @throws TransactionException
     */
    public function execute(array $data): void
    {
        $payerWallet = $this->repository->getBalance($data['payer_id']);
        $payeeWallet = $this->repository->getBalance($data['payee_id']);

        if ($payerWallet->owner_id === $payeeWallet->owner_id) {
            throw TransactionException::isSameUser();
        }
    }
}
