<?php

declare(strict_types=1);

use Keenops\LaravelTcbCms\DTOs\CreateReferenceRequest;

it('creates a request with required fields', function () {
    $request = CreateReferenceRequest::make(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
    );

    expect($request->reference)->toBe('999MYREF001')
        ->and($request->name)->toBe('John Doe')
        ->and($request->mobile)->toBe('0712345678')
        ->and($request->message)->toBe('Invoice #12345')
        ->and($request->amount)->toBeNull()
        ->and($request->expiryDate)->toBeNull();
});

it('creates a request with optional fields', function () {
    $request = CreateReferenceRequest::make(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
        amount: 50000.00,
        expiryDate: '2024-12-31',
    );

    expect($request->amount)->toBe(50000.00)
        ->and($request->expiryDate)->toBe('2024-12-31');
});

it('converts to array with required fields only', function () {
    $request = CreateReferenceRequest::make(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
    );

    $array = $request->toArray();

    expect($array)->toBe([
        'reference' => '999MYREF001',
        'name' => 'John Doe',
        'mobile' => '0712345678',
        'message' => 'Invoice #12345',
    ]);
});

it('converts to array with optional fields', function () {
    $request = CreateReferenceRequest::make(
        reference: '999MYREF001',
        name: 'John Doe',
        mobile: '0712345678',
        message: 'Invoice #12345',
        amount: 50000.00,
        expiryDate: '2024-12-31',
    );

    $array = $request->toArray();

    expect($array)->toBe([
        'reference' => '999MYREF001',
        'name' => 'John Doe',
        'mobile' => '0712345678',
        'message' => 'Invoice #12345',
        'amount' => 50000.00,
        'expiryDate' => '2024-12-31',
    ]);
});
