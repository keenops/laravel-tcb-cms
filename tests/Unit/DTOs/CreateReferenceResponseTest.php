<?php

declare(strict_types=1);

use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;
use Keenops\LaravelTcbCms\Enums\ResponseStatus;

it('creates response from successful array', function () {
    $response = CreateReferenceResponse::fromArray([
        'status' => 0,
        'message' => 'Reference created successfully',
        'accountNo' => '240123456789',
        'referenceNo' => '999MYREF001',
        'partnerCode' => 'TEST-PARTNER',
    ]);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->status)->toBe(ResponseStatus::Success)
        ->and($response->message)->toBe('Reference created successfully')
        ->and($response->accountNo)->toBe('240123456789')
        ->and($response->referenceNo)->toBe('999MYREF001')
        ->and($response->partnerCode)->toBe('TEST-PARTNER');
});

it('creates response from failed array', function () {
    $response = CreateReferenceResponse::fromArray([
        'status' => 1,
        'message' => 'Duplicate reference number',
    ]);

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->status)->toBe(ResponseStatus::Failure)
        ->and($response->message)->toBe('Duplicate reference number')
        ->and($response->accountNo)->toBeNull()
        ->and($response->referenceNo)->toBeNull();
});

it('handles missing fields gracefully', function () {
    $response = CreateReferenceResponse::fromArray([]);

    expect($response->status)->toBe(ResponseStatus::Failure)
        ->and($response->message)->toBe('Unknown response');
});

it('converts to array', function () {
    $response = CreateReferenceResponse::fromArray([
        'status' => 0,
        'message' => 'Success',
        'accountNo' => '240123456789',
        'referenceNo' => '999MYREF001',
        'partnerCode' => 'TEST-PARTNER',
    ]);

    expect($response->toArray())->toBe([
        'status' => 0,
        'message' => 'Success',
        'accountNo' => '240123456789',
        'referenceNo' => '999MYREF001',
        'partnerCode' => 'TEST-PARTNER',
    ]);
});
