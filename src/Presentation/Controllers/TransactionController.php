<?php

namespace PicPay\Presentation\Controllers;

use App\Http\Controllers\Controller;
use PicPay\Application\Controllers\TransferMoney;
use PicPay\Domain\Exception\AuthorizationException;
use PicPay\Domain\Exception\TransactionException;
use PicPay\Presentation\Request\TransactionRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(private readonly TransferMoney $transferMoney)
    {
    }

    public function transfer(TransactionRequest $request): JsonResponse
    {
        try {
            return response()->json($this->transferMoney->handle($request->all()), Response::HTTP_CREATED);
        } catch (TransactionException|AuthorizationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
