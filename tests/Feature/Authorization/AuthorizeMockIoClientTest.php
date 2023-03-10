<?php

namespace Tests\Feature\Authorization;

use Illuminate\Support\Facades\Http;
use PicPay\Domain\Exception\AuthorizationException;
use PicPay\Infrastructure\Models\User;
use PicPay\Infrastructure\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthorizeMockIoClientTest extends TestCase
{
    private User $user;
    private User $userWithoutBalance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Wallet::factory()->state(['balance' => 500]))->create();
        $this->userWithoutBalance = User::factory()->has(Wallet::factory()->state(['balance' => 0]))->create();
    }

    public function test_the_service_is_unauthorized()
    {
        $response = Http::fake([
            config("authorize.url") => Http::response([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED)
        ]);

        $response = $this->post(route('transaction.pay'), [
            'payer_id' => $this->user->id,
            'payee_id' => $this->userWithoutBalance->id,
            'amount' => 100
        ]);

        $exception = AuthorizationException::unauthorizedTransaction();

        $response->assertStatus($exception->getCode());
        $response->assertSee($exception->getMessage());
    }
}
