<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Keenops\LaravelTcbCms\Events\PaymentReceived;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

it('handles IPN callback successfully', function () {
    Event::fake([PaymentReceived::class]);

    $payload = [
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
    ];

    $response = $this->postJson('/tcb-cms/ipn', $payload);

    $response->assertOk()
        ->assertJson([
            'status' => 'success',
            'message' => 'Payment notification received',
        ]);

    Event::assertDispatched(PaymentReceived::class, function ($event) {
        return $event->payload->transactionId === 'TXN123456'
            && $event->payload->reference === '999MYREF001'
            && $event->payload->amount === 50000.00;
    });
});

it('logs IPN callback to the database', function () {
    Event::fake([PaymentReceived::class]);

    $payload = [
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
        'currency' => 'TZS',
        'transactionDate' => '2024-01-15T10:30:00Z',
        'payerName' => 'John Doe',
    ];

    $this->postJson('/tcb-cms/ipn', $payload);

    expect(TcbTransaction::count())->toBe(1);

    $transaction = TcbTransaction::first();
    expect($transaction->type)->toBe('ipn')
        ->and($transaction->reference)->toBe('999MYREF001')
        ->and($transaction->status)->toBe('success');
});

it('rejects IPN from unauthorized IP when IP verification is enabled', function () {
    config(['tcb-cms.verify_ip' => true]);
    config(['tcb-cms.allowed_ips' => ['192.168.1.1']]);

    Event::fake([PaymentReceived::class]);

    $response = $this->postJson('/tcb-cms/ipn', [
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
    ]);

    $response->assertForbidden()
        ->assertJson([
            'status' => 'error',
            'message' => 'Unauthorized IP address',
        ]);

    Event::assertNotDispatched(PaymentReceived::class);
});

it('accepts IPN when IP verification is disabled', function () {
    config(['tcb-cms.verify_ip' => false]);

    Event::fake([PaymentReceived::class]);

    $response = $this->postJson('/tcb-cms/ipn', [
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
    ]);

    $response->assertOk();

    Event::assertDispatched(PaymentReceived::class);
});

it('accepts IPN when allowed IPs list is empty', function () {
    config(['tcb-cms.verify_ip' => true]);
    config(['tcb-cms.allowed_ips' => []]);

    Event::fake([PaymentReceived::class]);

    $response = $this->postJson('/tcb-cms/ipn', [
        'transactionId' => 'TXN123456',
        'reference' => '999MYREF001',
        'amount' => 50000.00,
    ]);

    $response->assertOk();

    Event::assertDispatched(PaymentReceived::class);
});
