<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Keenops\LaravelTcbCms\DTOs\IpnPayload;

class PaymentReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly IpnPayload $payload,
    ) {}
}
