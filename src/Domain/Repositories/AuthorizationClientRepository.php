<?php

namespace PicPay\Domain\Repositories;

interface AuthorizationClientRepository
{
    public function authorize(int $attempt);
}
