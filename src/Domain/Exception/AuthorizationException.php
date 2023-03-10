<?php

namespace PicPay\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationException extends Exception
{
    public static function unauthorizedTransaction(): self
    {
        return new self("Unauthorized transaction", Response::HTTP_UNAUTHORIZED);
    }

    public static function theServiceUnavailable(): self
    {
        return new self("We had an unexpected error", Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
