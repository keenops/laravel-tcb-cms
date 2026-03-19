<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Contracts;

use Keenops\LaravelTcbCms\DTOs\CancelReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\ReconciliationRequest;
use Keenops\LaravelTcbCms\DTOs\ReconciliationResponse;

interface TcbCmsClientInterface
{
    /**
     * Create a payment reference.
     */
    public function createReference(CreateReferenceRequest $request): CreateReferenceResponse;

    /**
     * Cancel a payment reference.
     */
    public function cancelReference(CancelReferenceRequest $request): CancelReferenceResponse;

    /**
     * Perform reconciliation for a date range.
     */
    public function reconcile(ReconciliationRequest $request): ReconciliationResponse;
}
