<?php

namespace PicPay\Domain\Repositories;

interface NotifyClientRepository
{
    public function notify(string $transaction, int $retry);
}
