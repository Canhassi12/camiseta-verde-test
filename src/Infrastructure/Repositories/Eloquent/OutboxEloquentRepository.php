<?php

namespace PicPay\Infrastructure\Repositories\Eloquent;

use PicPay\Domain\Repositories\OutboxRepository;
use PicPay\Infrastructure\Models\Outbox;

class OutboxEloquentRepository implements OutboxRepository
{

    public function create(string $transactionId, string $type): void
    {
        Outbox::query()->create([
            'transaction_id' => $transactionId,
            'type' => $type
        ]);
    }

    public function getAll(): array
    {
        return Outbox::all()->toArray();
    }

    public function delete(string $id): void
    {
        Outbox::find($id)->delete();
    }

    public function update($id, array $data): void
    {
        Outbox::find($id)->update($data);
    }
}
