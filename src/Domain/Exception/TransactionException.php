<?php

namespace PicPay\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TransactionException extends Exception
{
    public static function insufficientFunds(): self
    {
        return new self('insufficient Funds in payer wallet :(', Response::HTTP_NOT_FOUND);
    }

    public static function sellerCannotBePayer(): self
    {
        return new self("The seller cannot be a payer in this transaction", Response::HTTP_NOT_FOUND);
    }

    public static function isSameUser(): self
    {
        return new self("The payer and payee cannot be the same user", Response::HTTP_NOT_FOUND);
    }
}
