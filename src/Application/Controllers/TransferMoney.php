<?php

namespace PicPay\Application\Controllers;

use PicPay\Domain\Entities\TransactionEntity;
use PicPay\Domain\Exception\TransactionException;
use PicPay\Domain\Repositories\TransactionRepository;
use PicPay\Domain\Service\Transaction\IsNotDifferentUser;
use PicPay\Domain\Service\Transaction\IsNotSeller;
use PicPay\Domain\Service\Transaction\VerifyMoneyInWallet;

class TransferMoney
{
    public function __construct(
        private readonly IsNotSeller $isNotSeller,
        private readonly IsNotDifferentUser $isNotDifferentUser,
        private readonly VerifyMoneyInWallet $verifyMoneyInWallet,
        private readonly TransactionRepository $transactionRepository,
    )
    {}

    /**
     * @throws TransactionException
     */
    public function handle(array $data): TransactionEntity
    {
        $this->isNotSeller->execute($data);

        $this->isNotDifferentUser->execute($data);

        $this->verifyMoneyInWallet->execute($data);

        return $this->transactionRepository->create($data);
    }
}
