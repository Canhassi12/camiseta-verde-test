<?php

namespace PicPay\Domain\Repositories;

use PicPay\Infrastructure\Models\Seller;

interface SellerRepository
{
    public function getSeller(string $uuid): ?Seller;
}
