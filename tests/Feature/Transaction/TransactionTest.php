<?php

namespace Tests\Feature\Transaction;

use Illuminate\Support\Facades\Http;
use PicPay\Domain\Exception\TransactionException;
use PicPay\Infrastructure\Models\Seller;
use PicPay\Infrastructure\Models\User;
use PicPay\Infrastructure\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private User $user;
    private User $userWithoutBalance;
    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->has(Wallet::factory()->state(['balance' => 500]))->create();
        $this->userWithoutBalance = User::factory()->has(Wallet::factory()->state(['balance' => 0]))->create();
        $this->seller = Seller::factory()->has(Wallet::factory()->state(['balance' => 0]))->create();
    }

    public function test_transaction_between_users()
    {
        Http::fake([
            config("authorize.url") => Http::response([
                "message" => "Autorizado"
            ], Response::HTTP_UNAUTHORIZED)
        ]);

        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->user->id,
            'payee_id' => $this->userWithoutBalance->id,
            'amount' => 100
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'payer_id' => $this->user->id,
            'payee_id' => $this->userWithoutBalance->id,
            'amount' => 100
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->user->id,
            'balance' => 400
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->userWithoutBalance->id,
            'balance' => 100
        ]);
    }

    public function test_transaction_with_a_user_and_seller()
    {
        $response = Http::fake([
            config("authorize.url") => Http::response([
                "message" => "Autorizado"
            ], Response::HTTP_UNAUTHORIZED)
        ]);

        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->user->id,
            'payee_id' => $this->seller->id,
            'amount' => 100
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'payer_id' => $this->user->id,
            'payee_id' => $this->seller->id,
            'amount' => 100
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->user->id,
            'balance' => 400
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->seller->id,
            'balance' => 100
        ]);
    }

    public function test_user_should_do_a_transaction_without_money()
    {
        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->userWithoutBalance->id,
            'payee_id' => $this->seller->id,
            'amount' => 100
        ]);

        $exception = TransactionException::InsufficientFunds();

        $response->assertStatus($exception->getCode());
        $response->assertSee($exception->getMessage());
    }

    public function test_seller_cannot_be_payer()
    {
        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->seller->id,
            'payee_id' => $this->user->id,
            'amount' => 100
        ]);

        $exception = TransactionException::SellerCannotBePayer();

        $response->assertSee($exception->getMessage());
        $response->assertStatus($exception->getCode());
    }

    public function test_payer_not_be_payee()
    {
        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->user->id,
            'payee_id' => $this->user->id,
            'amount' => 100
        ]);

        $exception = TransactionException::isSameUser();

        $response->assertSee($exception->getMessage());
        $response->assertStatus($exception->getCode());
    }
}
