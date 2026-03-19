<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

use Keenops\LaravelTcbCms\Enums\ResponseStatus;

readonly class CreateReferenceResponse
{
    public function __construct(
        public ResponseStatus $status,
        public string $message,
        public ?string $accountNo = null,
        public ?string $referenceNo = null,
        public ?string $partnerCode = null,
    ) {}

    /**
     * Create from API response array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: ResponseStatus::from((int) ($data['status'] ?? 1)),
            message: $data['message'] ?? 'Unknown response',
            accountNo: $data['accountNo'] ?? null,
            referenceNo: $data['referenceNo'] ?? null,
            partnerCode: $data['partnerCode'] ?? null,
        );
    }

    public function isSuccessful(): bool
    {
        return $this->status->isSuccessful();
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'message' => $this->message,
            'accountNo' => $this->accountNo,
            'referenceNo' => $this->referenceNo,
            'partnerCode' => $this->partnerCode,
        ];
    }
}
