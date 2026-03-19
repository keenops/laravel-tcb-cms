<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

readonly class CreateReferenceRequest
{
    public function __construct(
        public string $reference,
        public string $name,
        public string $mobile,
        public string $message,
        public ?float $amount = null,
        public ?string $expiryDate = null,
    ) {}

    public static function make(
        string $reference,
        string $name,
        string $mobile,
        string $message,
        ?float $amount = null,
        ?string $expiryDate = null,
    ): self {
        return new self(
            reference: $reference,
            name: $name,
            mobile: $mobile,
            message: $message,
            amount: $amount,
            expiryDate: $expiryDate,
        );
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'reference' => $this->reference,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'message' => $this->message,
        ];

        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }

        if ($this->expiryDate !== null) {
            $data['expiryDate'] = $this->expiryDate;
        }

        return $data;
    }
}
