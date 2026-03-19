<?php

declare(strict_types=1);

use Keenops\LaravelTcbCms\Enums\ResponseStatus;

it('has correct values', function () {
    expect(ResponseStatus::Success->value)->toBe(0)
        ->and(ResponseStatus::Failure->value)->toBe(1)
        ->and(ResponseStatus::ConnectionError->value)->toBe(2)
        ->and(ResponseStatus::ApiKeyError->value)->toBe(4);
});

it('returns correct success status', function () {
    expect(ResponseStatus::Success->isSuccessful())->toBeTrue()
        ->and(ResponseStatus::Failure->isSuccessful())->toBeFalse()
        ->and(ResponseStatus::ConnectionError->isSuccessful())->toBeFalse()
        ->and(ResponseStatus::ApiKeyError->isSuccessful())->toBeFalse();
});

it('returns correct labels', function () {
    expect(ResponseStatus::Success->label())->toBe('Success')
        ->and(ResponseStatus::Failure->label())->toBe('Failure')
        ->and(ResponseStatus::ConnectionError->label())->toBe('Connection Error')
        ->and(ResponseStatus::ApiKeyError->label())->toBe('Invalid API Key');
});
