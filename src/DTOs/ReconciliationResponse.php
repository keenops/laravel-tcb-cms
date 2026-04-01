<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

use Illuminate\Support\Collection;
use Keenops\LaravelTcbCms\Enums\ResponseStatus;

readonly class ReconciliationResponse implements \JsonSerializable
{
    /**
     * @param  Collection<int, ReconciliationItem>  $transactions
     */
    public function __construct(
        public ResponseStatus $status,
        public string $message,
        public Collection $transactions,
        public int $totalCount = 0,
        public float $totalAmount = 0,
    ) {}

    /**
     * Create from API response array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $transactions = collect($data['transactions'] ?? [])
            ->map(fn (array $item) => ReconciliationItem::fromArray($item));

        return new self(
            status: ResponseStatus::from((int) ($data['status'] ?? 1)),
            message: $data['message'] ?? 'Unknown response',
            transactions: $transactions,
            totalCount: (int) ($data['totalCount'] ?? $transactions->count()),
            totalAmount: (float) ($data['totalAmount'] ?? $transactions->sum('amount')),
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
            'transactions' => $this->transactions->map(fn (ReconciliationItem $item) => $item->toArray())->all(),
            'totalCount' => $this->totalCount,
            'totalAmount' => $this->totalAmount,
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
