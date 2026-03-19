<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Exceptions;

class ApiConnectionException extends TcbCmsException
{
    public function __construct(
        string $message = 'Failed to connect to TCB CMS API',
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
