<?php

declare(strict_types=1);

use Carbon\Carbon;
use Keenops\LaravelTcbCms\DTOs\IpnPayload;

it('creates payload from array', function () {
    $payload = IpnPayload::fromArray([
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
        'currency' => 'TZS',
        'transactionDate' => '2024-01-15T10:30:00Z',
        'payerName' => 'John Doe',
        'payerMobile' => '0712345678',
        'payerAccount' => '123456789',
        'channel' => 'TCB_MOBILE',
        'accountNo' => '240123456789',
        'partnerCode' => 'TEST-PARTNER',
    ]);

    expect($payload->transactionId)->toBe('TXN123456')
        ->and($payload->reference)->toBe('999MYREF001')
        ->and($payload->amount)->toBe(50000.00)
        ->and($payload->currency)->toBe('TZS')
        ->and($payload->payerName)->toBe('John Doe')
        ->and($payload->payerMobile)->toBe('0712345678')
        ->and($payload->payerAccount)->toBe('123456789')
        ->and($payload->channel)->toBe('TCB_MOBILE')
        ->and($payload->accountNo)->toBe('240123456789')
        ->and($payload->partnerCode)->toBe('TEST-PARTNER')
        ->and($payload->transactionDate)->toBeInstanceOf(Carbon::class);
});

it('handles missing optional fields', function () {
    $payload = IpnPayload::fromArray([
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
        'payerName' => 'John Doe',
    ]);

    expect($payload->payerMobile)->toBeNull()
        ->and($payload->payerAccount)->toBeNull()
        ->and($payload->channel)->toBeNull()
        ->and($payload->accountNo)->toBeNull()
        ->and($payload->partnerCode)->toBeNull()
        ->and($payload->currency)->toBe('TZS');
});

it('uses current date when transactionDate is missing', function () {
    Carbon::setTestNow('2024-01-15 10:30:00');

    $payload = IpnPayload::fromArray([
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
        'payerName' => 'John Doe',
    ]);

    expect($payload->transactionDate->toDateTimeString())->toBe('2024-01-15 10:30:00');

    Carbon::setTestNow();
});

it('converts to array', function () {
    Carbon::setTestNow('2024-01-15 10:30:00');

    $payload = IpnPayload::fromArray([
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
        'currency' => 'TZS',
        'payerName' => 'John Doe',
    ]);

    $array = $payload->toArray();

    expect($array['transactionId'])->toBe('TXN123456')
        ->and($array['reference'])->toBe('999MYREF001')
        ->and($array['amount'])->toBe(50000.00)
        ->and($array['currency'])->toBe('TZS')
        ->and($array['payerName'])->toBe('John Doe');

    Carbon::setTestNow();
});
