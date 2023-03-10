<?php

namespace PicPay\Domain\Repositories;

interface OutboxRepository
{
    public function create(string $transactionId, string $type): void;

    public function getAll(): array;

    public function delete(string $id): void;

    public function update($id, array $data): void;
}
