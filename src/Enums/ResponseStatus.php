<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Enums;

enum ResponseStatus: int
{
    case Success = 0;
    case Failure = 1;
    case ConnectionError = 2;
    case ApiKeyError = 4;
    case ReferenceNotFound = 96;

    public function isSuccessful(): bool
    {
        return $this === self::Success;
    }

    public function label(): string
    {
        return match ($this) {
            self::Success => 'Success',
            self::Failure => 'Failure',
            self::ConnectionError => 'Connection Error',
            self::ApiKeyError => 'Invalid API Key',
            self::ReferenceNotFound => 'Reference Not Found',
        };
    }
}
