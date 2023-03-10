<?php

namespace PicPay\Infrastructure\Repositories\Eloquent;

use PicPay\Domain\Repositories\SellerRepository;
use PicPay\Infrastructure\Models\Seller;

class SellerEloquentRepository implements SellerRepository
{
    public function getSeller(string $uuid): ?Seller
    {
        return Seller::where('id', $uuid)->first();
    }
}
