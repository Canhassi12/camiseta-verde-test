<?php

namespace PicPay\Presentation\Routes;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use PicPay\Presentation\Controllers\TransactionController;

class Router extends RouteServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Route::middleware(['api'])->prefix('products')->group(function () {
            Route::post('/transaction', [TransactionController::class, 'transfer'])->name('transaction.pay');
        });
    }
}
