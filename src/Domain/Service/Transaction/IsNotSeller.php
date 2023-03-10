<?php

namespace PicPay\Domain\Service\Transaction;

use PicPay\Domain\Exception\TransactionException;
use PicPay\Domain\Repositories\SellerRepository;

class IsNotSeller
{
    public function __construct(private readonly SellerRepository $repository)
    {
    }

    /**
     * @throws TransactionException
     */
    public function execute(array $data): void
    {
        if ($this->repository->getSeller($data['payer_id'])) {
            throw TransactionException::sellerCannotBePayer();
        }
    }
}
