<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

use Carbon\Carbon;
use Carbon\CarbonInterface;

readonly class ReconciliationItem
{
    public function __construct(
        public string $transactionId,
        public string $reference,
        public float $amount,
        public string $currency,
        public CarbonInterface $transactionDate,
        public string $payerName,
        public ?string $payerMobile = null,
        public ?string $channel = null,
        public ?string $status = null,
    ) {}

    /**
     * Create from API response array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            transactionId: $data['transactionId'] ?? '',
            reference: $data['reference'] ?? '',
            amount: (float) ($data['amount'] ?? 0),
            currency: $data['currency'] ?? 'TZS',
            transactionDate: isset($data['transactionDate'])
                ? Carbon::parse($data['transactionDate'])
                : Carbon::now(),
            payerName: $data['payerName'] ?? '',
            payerMobile: $data['payerMobile'] ?? null,
            channel: $data['channel'] ?? null,
            status: $data['status'] ?? null,
        );
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'transactionId' => $this->transactionId,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'transactionDate' => $this->transactionDate->toIso8601String(),
            'payerName' => $this->payerName,
            'payerMobile' => $this->payerMobile,
            'channel' => $this->channel,
            'status' => $this->status,
        ];
    }
}
