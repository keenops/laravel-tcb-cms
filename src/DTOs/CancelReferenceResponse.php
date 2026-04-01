<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

use Keenops\LaravelTcbCms\Enums\ResponseStatus;

readonly class CancelReferenceResponse implements \JsonSerializable
{
    public function __construct(
        public ResponseStatus $status,
        public string $message,
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
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
