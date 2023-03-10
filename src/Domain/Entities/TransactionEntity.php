<?php

namespace PicPay\Domain\Entities;

class TransactionEntity
{
    public function __construct(
        public readonly string $id,
        public readonly int $amount,
        public readonly string $payerId,
        public readonly string $payeeId,
    ) {
    }

    public static function make(array $payload): self
    {
        return new self(
            id: $payload['id'],
            amount: $payload['amount'],
            payerId: $payload['payer_id'],
            payeeId: $payload['payee_id']
        );
    }
}
