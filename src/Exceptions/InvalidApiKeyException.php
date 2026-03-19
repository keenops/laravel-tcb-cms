<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Exceptions;

class InvalidApiKeyException extends TcbCmsException
{
    public function __construct(
        string $message = 'Invalid TCB CMS API key',
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
