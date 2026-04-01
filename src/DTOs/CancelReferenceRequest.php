<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

readonly class CancelReferenceRequest
{
    public function __construct(
        public string $referenceNo,
    ) {}

    public static function make(string $referenceNo): self
    {
        return new self(referenceNo: $referenceNo);
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'refNo' => $this->referenceNo,
        ];
    }
}
