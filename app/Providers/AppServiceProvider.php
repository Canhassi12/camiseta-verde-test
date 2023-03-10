<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PicPay\Domain\Repositories\AuthorizationClientRepository;
use PicPay\Domain\Repositories\OutboxRepository;
use PicPay\Domain\Repositories\SellerRepository;
use PicPay\Domain\Repositories\TransactionRepository;
use PicPay\Domain\Repositories\WalletRepository;
use PicPay\Domain\Service\Authorization\AuthorizationMockIoClientRepository;
use PicPay\Infrastructure\Repositories\Eloquent\OutboxEloquentRepository;
use PicPay\Infrastructure\Repositories\Eloquent\SellerEloquentRepository;
use PicPay\Infrastructure\Repositories\Eloquent\TransactionEloquentRepository;
use PicPay\Infrastructure\Repositories\Eloquent\WalletEloquentRepository;
use PicPay\Presentation\Routes\Router;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(Router::class);
        $this->app->bind(TransactionRepository::class, TransactionEloquentRepository::class);
        $this->app->bind(WalletRepository::class, WalletEloquentRepository::class);
        $this->app->bind(SellerRepository::class, SellerEloquentRepository::class);
        $this->app->bind(OutboxRepository::class, OutboxEloquentRepository::class);
        $this->app->bind(AuthorizationClientRepository::class, AuthorizationMockIoClientRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
