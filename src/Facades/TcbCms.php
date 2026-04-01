<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Facades;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\ReconciliationResponse;

/**
 * @method static CreateReferenceResponse createReference(string $reference, string $name, string $mobile, string $message, ?float $amount = null, ?string $expiryDate = null)
 * @method static CancelReferenceResponse cancelReference(string $referenceNo)
 * @method static ReconciliationResponse reconcile(CarbonInterface $startDate, CarbonInterface $endDate)
 *
 * @see \Keenops\LaravelTcbCms\TcbCms
 */
class TcbCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Keenops\LaravelTcbCms\TcbCms::class;
    }
}
