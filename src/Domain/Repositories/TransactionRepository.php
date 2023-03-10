<?php

namespace PicPay\Domain\Repositories;

use PicPay\Domain\Entities\TransactionEntity;

interface TransactionRepository
{
    public function create(array $data): TransactionEntity;
}
