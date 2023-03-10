<?php

namespace PicPay\Domain\Service\Authorization;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PicPay\Domain\Exception\AuthorizationException;
use PicPay\Domain\Repositories\AuthorizationClientRepository;
use PicPay\Domain\Service\Retries;

class AuthorizationMockIoClientRepository implements AuthorizationClientRepository
{
    private int $maxRetries = 3;
    private int $initialDelay = 2;
    private int $maxDelay = 10;

    /**
     * @throws AuthorizationException
     */
    private function unauthorizedService()
    {
        throw AuthorizationException::UnauthorizedTransaction();
    }

    /**
     * @throws AuthorizationException
     */
    public function authorize(int $attempt)
    {
        $retries = new Retries($this->maxRetries, $this->initialDelay, $this->maxDelay);
        $response = Http::get(config("authorize.url"));

        if ($response->json()["message"] != 'Autorizado') {
            $this->unauthorizedService();
        }

        if ($response->json()["message"] == 'Autorizado') {
            Log::info("Authorization success");
            return;
        }

        $attempt = $retries->retry($attempt);

        $this->authorize($attempt);
    }
}
