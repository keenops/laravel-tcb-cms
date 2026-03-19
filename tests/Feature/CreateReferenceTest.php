<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Keenops\LaravelTcbCms\Events\ReferenceCreated;
use Keenops\LaravelTcbCms\Facades\TcbCms;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

it('creates a payment reference successfully', function () {
    Http::fake([
        '*/api/v1/cms/reference/create' => Http::response([
            'status' => 0,
            'message' => 'Reference created successfully',
            'accountNo' => '240123456789',
            'referenceNo' => '999MYREF001',
            'partnerCode' => 'TEST-PARTNER',
        ], 200),
    ]);

    Event::fake([ReferenceCreated::class]);

    $response = TcbCms::createReference(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->accountNo)->toBe('240123456789')
        ->and($response->referenceNo)->toBe('999MYREF001')
        ->and($response->partnerCode)->toBe('TEST-PARTNER');

    Event::assertDispatched(ReferenceCreated::class);
});

it('logs successful reference creation to the database', function () {
    Http::fake([
        '*/api/v1/cms/reference/create' => Http::response([
            'status' => 0,
            'message' => 'Reference created successfully',
            'accountNo' => '240123456789',
            'referenceNo' => '999MYREF001',
            'partnerCode' => 'TEST-PARTNER',
        ], 200),
    ]);

    TcbCms::createReference(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
    );

    expect(TcbTransaction::count())->toBe(1);

    $transaction = TcbTransaction::first();
    expect($transaction->type)->toBe('create_reference')
        ->and($transaction->reference)->toBe('999MYREF001')
        ->and($transaction->status)->toBe('success');
});

it('handles failed reference creation', function () {
    Http::fake([
        '*/api/v1/cms/reference/create' => Http::response([
            'status' => 1,
            'message' => 'Duplicate reference number',
        ], 200),
    ]);

    Event::fake([ReferenceCreated::class]);

    $response = TcbCms::createReference(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
    );

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->message)->toBe('Duplicate reference number');

    Event::assertNotDispatched(ReferenceCreated::class);

    $transaction = TcbTransaction::first();
    expect($transaction->status)->toBe('failure')
        ->and($transaction->error_message)->toBe('Duplicate reference number');
});

it('creates reference with optional amount and expiry date', function () {
    Http::fake([
        '*/api/v1/cms/reference/create' => Http::response([
            'status' => 0,
            'message' => 'Reference created successfully',
            'accountNo' => '240123456789',
            'referenceNo' => '999MYREF001',
            'partnerCode' => 'TEST-PARTNER',
        ], 200),
    ]);

    $response = TcbCms::createReference(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
        amount: 50000.00,
        expiryDate: '2024-12-31',
    );

    expect($response->isSuccessful())->toBeTrue();

    Http::assertSent(function ($request) {
        $body = $request->data();

        return $body['amount'] === 50000.00
            && $body['expiryDate'] === '2024-12-31';
    });
});
