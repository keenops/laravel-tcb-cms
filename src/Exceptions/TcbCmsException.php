<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Exceptions;

use Exception;

class TcbCmsException extends Exception
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        string $message = 'TCB CMS API error',
        int $code = 0,
        ?\Throwable $previous = null,
        public readonly array $context = [],
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the exception context for logging.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return $this->context;
    }
}
