<?php

namespace PicPay\Infrastructure\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PicPay\Domain\Entities\TransactionEntity;
use PicPay\Domain\Repositories\AuthorizationClientRepository;
use PicPay\Domain\Repositories\OutboxRepository;
use PicPay\Domain\Repositories\TransactionRepository;
use PicPay\Domain\Service\Wallet\UpdateWallet;
use PicPay\Infrastructure\Models\Transaction;

class TransactionEloquentRepository implements TransactionRepository
{
    public function __construct(
        private readonly AuthorizationClientRepository $client,
        private readonly OutboxRepository              $outboxRepository,
        private readonly UpdateWallet                  $updateWallet,
    )
    {}

    public function create(array $data): TransactionEntity
    {

        $this->client->authorize(0);

        return DB::transaction(function() use ($data)
        {
            $this->updateWallet->handle($data['payer_id'], $data['payee_id'], $data['amount']);

            $transaction = Transaction::query()->create($data);
            Log::info("Transaction created: " . json_encode($transaction));

            $this->outboxRepository->create($transaction['id'], 'transfer');

            return TransactionEntity::make($transaction->toArray());
        });
    }
}
