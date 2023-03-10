<?php

namespace PicPay\Domain\Service;

use PicPay\Domain\Exception\AuthorizationException;
use PicPay\Domain\Service;

class Retries
{
    public function __construct(
        private readonly int $maxRetries,
        private readonly int $initialDelay,
        private readonly int $maxDelay
    )
    {}

    /**
     * @throws AuthorizationException
     */
    public function retry(int $attempt): int
    {
        if ($attempt >= $this->maxRetries) {
            throw AuthorizationException::theServiceUnavailable();
        }

        $delay = $this->initialDelay * 2 ** $attempt;
        $delay = min($delay, $this->maxDelay);
        sleep(rand(0, $delay));

        return $attempt + 1;
    }
}
