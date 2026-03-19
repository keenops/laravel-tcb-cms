<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Keenops\LaravelTcbCms\DTOs\ReconciliationRequest;
use Keenops\LaravelTcbCms\DTOs\ReconciliationResponse;

class ReconciliationCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ReconciliationRequest $request,
        public readonly ReconciliationResponse $response,
    ) {}
}
