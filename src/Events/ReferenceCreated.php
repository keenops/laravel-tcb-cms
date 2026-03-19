<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;

class ReferenceCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CreateReferenceRequest $request,
        public readonly CreateReferenceResponse $response,
    ) {}
}
