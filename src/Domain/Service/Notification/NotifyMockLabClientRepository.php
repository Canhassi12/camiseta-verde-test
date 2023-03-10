<?php

namespace PicPay\Domain\Service\Notification;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PicPay\Domain\Repositories\NotifyClientRepository;
use PicPay\Domain\Service\Retries;

class NotifyMockLabClientRepository implements NotifyClientRepository
{
    private Retries $retries;

    public function __construct()
    {
        $this->retries = new Retries(3, 2, 10);
    }

    public function notify(string $transactionId, int $attempt)
    {
        try {
            Http::get(config('notify.url'));
            Log::info("Notify success with transaction id {$transactionId}");

            return [$transactionId, true];
        } catch(Exception $e) {
            $attempt = $this->retries->retry($attempt);

            $this->notify($transactionId, $attempt + 1);
        }
    }
}
