<?php

declare(strict_types=1);

use Keenops\LaravelTcbCms\Enums\PaymentChannel;

it('has correct values', function () {
    expect(PaymentChannel::TcbMobile->value)->toBe('TCB_MOBILE')
        ->and(PaymentChannel::TcbBranch->value)->toBe('TCB_BRANCH')
        ->and(PaymentChannel::TcbAtm->value)->toBe('TCB_ATM')
        ->and(PaymentChannel::Ussd->value)->toBe('USSD')
        ->and(PaymentChannel::InternetBanking->value)->toBe('INTERNET_BANKING')
        ->and(PaymentChannel::AgentBanking->value)->toBe('AGENT_BANKING')
        ->and(PaymentChannel::Pesalink->value)->toBe('PESALINK');
});

it('returns correct labels', function () {
    expect(PaymentChannel::TcbMobile->label())->toBe('TCB Mobile Banking')
        ->and(PaymentChannel::TcbBranch->label())->toBe('TCB Branch')
        ->and(PaymentChannel::Ussd->label())->toBe('USSD Banking');
});

it('returns payment instructions with reference', function () {
    $reference = '999MYREF001';

    $instructions = PaymentChannel::TcbMobile->getPaymentInstructions($reference);

    expect($instructions)->toContain($reference)
        ->and($instructions)->toContain('TCB Mobile App');
});

it('returns all channels with instructions', function () {
    $reference = '999MYREF001';

    $channels = PaymentChannel::allWithInstructions($reference);

    expect($channels)->toHaveCount(7)
        ->and($channels['TCB_MOBILE'])->toHaveKeys(['label', 'instructions'])
        ->and($channels['TCB_MOBILE']['label'])->toBe('TCB Mobile Banking')
        ->and($channels['TCB_MOBILE']['instructions'])->toContain($reference);
});
