<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\DTOs;

use Carbon\CarbonInterface;

readonly class ReconciliationRequest
{
    public function __construct(
        public CarbonInterface $startDate,
        public CarbonInterface $endDate,
    ) {}

    public static function make(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
    ): self {
        return new self(
            startDate: $startDate,
            endDate: $endDate,
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
            'startDate' => $this->startDate->format('Y-m-d'),
            'endDate' => $this->endDate->format('Y-m-d'),
        ];
    }
}
