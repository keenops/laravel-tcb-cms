<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

readonly class CancelReferenceRequest
{
    public function __construct(
        public string $accountNo,
        public string $referenceNo,
    ) {}

    public static function make(
        string $accountNo,
        string $referenceNo,
    ): self {
        return new self(
            accountNo: $accountNo,
            referenceNo: $referenceNo,
        );
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'accountNo' => $this->accountNo,
            'referenceNo' => $this->referenceNo,
        ];
    }
}
