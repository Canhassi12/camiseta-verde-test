<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PicPay\Domain\Service\Notification\NotifyMockLabClientRepository;
use PicPay\Infrastructure\Repositories\Eloquent\OutboxEloquentRepository;

class NotifyClientCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send notifications to clients';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $notifyClient = new NotifyMockLabClientRepository();
        $outbox = new OutboxEloquentRepository();

        $transactions = $outbox->getAll();

        foreach($transactions as $transaction) {
            if ($transaction['attempts'] >= 3) {
                Log::error("failed, attempts exceeded with transaction id {$transaction['id']}");
                break;
            }

            $response = $notifyClient->notify($transaction['id'], $transaction['attempts']);

            if ($response[1] === false || $response[1] === null) {
                log::error("failed to notify client");

                $outbox->update($response[0], ['attempts' => $transaction['attempts'] + 1]);
                break;
            }

            $outbox->delete($response[0]);
            log::info("notify deleted from outbox");
        }

        return 0;
    }
}
