<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Exceptions;

class InvalidReferenceException extends TcbCmsException
{
    public function __construct(
        string $message = 'Invalid payment reference',
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
