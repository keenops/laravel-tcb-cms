<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Keenops\LaravelTcbCms\Events\ReferenceCancelled;
use Keenops\LaravelTcbCms\Facades\TcbCms;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

it('cancels a payment reference successfully', function () {
    Http::fake([
        '*/public/api/reference/cancel/*' => Http::response([
            'status' => 0,
            'message' => 'Reference cancelled successfully',
        ], 200),
    ]);

    Event::fake([ReferenceCancelled::class]);

    $response = TcbCms::cancelReference(
        accountNo: '240123456789',
        referenceNo: '999MYREF001',
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->message)->toBe('Reference cancelled successfully');

    Event::assertDispatched(ReferenceCancelled::class);
});

it('logs successful reference cancellation to the database', function () {
    Http::fake([
        '*/public/api/reference/cancel/*' => Http::response([
            'status' => 0,
            'message' => 'Reference cancelled successfully',
        ], 200),
    ]);

    TcbCms::cancelReference(
        accountNo: '240123456789',
        referenceNo: '999MYREF001',
    );

    expect(TcbTransaction::count())->toBe(1);

    $transaction = TcbTransaction::first();
    expect($transaction->type)->toBe('cancel_reference')
        ->and($transaction->reference)->toBe('999MYREF001')
        ->and($transaction->status)->toBe('success');
});

it('handles failed reference cancellation', function () {
    Http::fake([
        '*/public/api/reference/cancel/*' => Http::response([
            'status' => 1,
            'message' => 'Reference not found',
        ], 200),
    ]);

    Event::fake([ReferenceCancelled::class]);

    $response = TcbCms::cancelReference(
        accountNo: '240123456789',
        referenceNo: '999MYREF001',
    );

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->message)->toBe('Reference not found');

    Event::assertNotDispatched(ReferenceCancelled::class);

    $transaction = TcbTransaction::first();
    expect($transaction->status)->toBe('failure')
        ->and($transaction->error_message)->toBe('Reference not found');
});

it('sends correct payload for reference cancellation', function () {
    Http::fake([
        '*/public/api/reference/cancel/*' => Http::response([
            'status' => 0,
            'message' => 'Reference cancelled successfully',
        ], 200),
    ]);

    TcbCms::cancelReference(
        accountNo: '240123456789',
        referenceNo: '999MYREF001',
    );

    Http::assertSent(function ($request) {
        $body = $request->data();

        return $body['accountNo'] === '240123456789'
            && $body['referenceNo'] === '999MYREF001'
            && $body['partnerCode'] === 'TEST-PARTNER';
    });
});
