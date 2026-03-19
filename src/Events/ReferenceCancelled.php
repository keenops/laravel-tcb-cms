<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceResponse;

class ReferenceCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CancelReferenceRequest $request,
        public readonly CancelReferenceResponse $response,
    ) {}
}
