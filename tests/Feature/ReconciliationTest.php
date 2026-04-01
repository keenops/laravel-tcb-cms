<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Keenops\LaravelTcbCms\Events\ReconciliationCompleted;
use Keenops\LaravelTcbCms\Facades\TcbCms;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

it('performs reconciliation successfully', function () {
    Http::fake([
        '*/public/api/reconciliation/*' => Http::response([
            'status' => 0,
            'message' => 'Reconciliation completed',
            'totalCount' => 2,
            'totalAmount' => 150000.00,
            'transactions' => [
                [
                    'transactionId' => 'TXN001',
                    'reference' => '999MYREF001',
                    'amount' => 50000.00,
                    'currency' => 'TZS',
                    'transactionDate' => '2024-01-15T10:30:00Z',
                    'payerName' => 'John Doe',
                    'payerMobile' => '0712345678',
                    'channel' => 'TCB_MOBILE',
                ],
                [
                    'transactionId' => 'TXN002',
                    'reference' => '999MYREF002',
                    'amount' => 100000.00,
                    'currency' => 'TZS',
                    'transactionDate' => '2024-01-16T14:45:00Z',
                    'payerName' => 'Jane Smith',
                    'payerMobile' => '0722345678',
                    'channel' => 'USSD',
                ],
            ],
        ], 200),
    ]);

    Event::fake([ReconciliationCompleted::class]);

    $response = TcbCms::reconcile(
        startDate: Carbon::parse('2024-01-01'),
        endDate: Carbon::parse('2024-01-31'),
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->totalCount)->toBe(2)
        ->and($response->totalAmount)->toBe(150000.00)
        ->and($response->transactions)->toHaveCount(2);

    $firstTransaction = $response->transactions->first();
    expect($firstTransaction->transactionId)->toBe('TXN001')
        ->and($firstTransaction->reference)->toBe('999MYREF001')
        ->and($firstTransaction->amount)->toBe(50000.00);

    Event::assertDispatched(ReconciliationCompleted::class);
});

it('logs reconciliation to the database', function () {
    Http::fake([
        '*/public/api/reconciliation/*' => Http::response([
            'status' => 0,
            'message' => 'Reconciliation completed',
            'totalCount' => 1,
            'totalAmount' => 50000.00,
            'transactions' => [],
        ], 200),
    ]);

    TcbCms::reconcile(
        startDate: Carbon::parse('2024-01-01'),
        endDate: Carbon::parse('2024-01-31'),
    );

    expect(TcbTransaction::count())->toBe(1);

    $transaction = TcbTransaction::first();
    expect($transaction->type)->toBe('reconciliation')
        ->and($transaction->reference)->toBeNull()
        ->and($transaction->status)->toBe('success');
});

it('handles failed reconciliation', function () {
    Http::fake([
        '*/public/api/reconciliation/*' => Http::response([
            'status' => 1,
            'message' => 'Invalid date range',
        ], 200),
    ]);

    Event::fake([ReconciliationCompleted::class]);

    $response = TcbCms::reconcile(
        startDate: Carbon::parse('2024-01-01'),
        endDate: Carbon::parse('2024-01-31'),
    );

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->message)->toBe('Invalid date range');

    Event::assertNotDispatched(ReconciliationCompleted::class);
});

it('sends correct date format in reconciliation request', function () {
    Http::fake([
        '*/public/api/reconciliation/*' => Http::response([
            'status' => 0,
            'message' => 'Reconciliation completed',
            'totalCount' => 0,
            'totalAmount' => 0,
            'transactions' => [],
        ], 200),
    ]);

    TcbCms::reconcile(
        startDate: Carbon::parse('2024-01-15'),
        endDate: Carbon::parse('2024-01-20'),
    );

    Http::assertSent(function ($request) {
        $body = $request->data();

        return $body['startDate'] === '2024-01-15'
            && $body['endDate'] === '2024-01-20'
            && $body['partnerCode'] === 'TEST-PARTNER';
    });
});
