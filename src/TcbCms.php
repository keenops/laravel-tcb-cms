<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms;

use Carbon\CarbonInterface;
use Keenops\LaravelTcbCms\Contracts\TcbCmsClientInterface;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\ReconciliationRequest;
use Keenops\LaravelTcbCms\DTOs\ReconciliationResponse;
use Keenops\LaravelTcbCms\Events\ReconciliationCompleted;
use Keenops\LaravelTcbCms\Events\ReferenceCancelled;
use Keenops\LaravelTcbCms\Events\ReferenceCreated;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

class TcbCms
{
    public function __construct(
        protected TcbCmsClientInterface $client,
    ) {}

    /**
     * Create a payment reference.
     */
    public function createReference(
        string $reference,
        string $name,
        string $mobile,
        string $message,
        ?float $amount = null,
        ?string $expiryDate = null,
    ): CreateReferenceResponse {
        $request = CreateReferenceRequest::make(
            reference: $reference,
            name: $name,
            mobile: $mobile,
            message: $message,
            amount: $amount,
            expiryDate: $expiryDate,
        );

        $response = $this->client->createReference($request);

        $this->logTransaction(
            type: 'create_reference',
            reference: $reference,
            status: $response->isSuccessful() ? 'success' : 'failure',
            request: $request->toArray(),
            response: $response->toArray(),
            errorMessage: $response->isSuccessful() ? null : $response->message,
        );

        if ($response->isSuccessful()) {
            event(new ReferenceCreated($request, $response));
        }

        return $response;
    }

    /**
     * Cancel a payment reference.
     */
    public function cancelReference(string $referenceNo): CancelReferenceResponse
    {
        $request = CancelReferenceRequest::make(referenceNo: $referenceNo);

        $response = $this->client->cancelReference($request);

        $this->logTransaction(
            type: 'cancel_reference',
            reference: $referenceNo,
            status: $response->isSuccessful() ? 'success' : 'failure',
            request: $request->toArray(),
            response: $response->toArray(),
            errorMessage: $response->isSuccessful() ? null : $response->message,
        );

        if ($response->isSuccessful()) {
            event(new ReferenceCancelled($request, $response));
        }

        return $response;
    }

    /**
     * Perform reconciliation for a date range.
     */
    public function reconcile(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
    ): ReconciliationResponse {
        $request = ReconciliationRequest::make(
            startDate: $startDate,
            endDate: $endDate,
        );

        $response = $this->client->reconcile($request);

        $this->logTransaction(
            type: 'reconciliation',
            reference: null,
            status: $response->isSuccessful() ? 'success' : 'failure',
            request: $request->toArray(),
            response: [
                'status' => $response->status->value,
                'message' => $response->message,
                'totalCount' => $response->totalCount,
                'totalAmount' => $response->totalAmount,
            ],
            errorMessage: $response->isSuccessful() ? null : $response->message,
        );

        if ($response->isSuccessful()) {
            event(new ReconciliationCompleted($request, $response));
        }

        return $response;
    }

    /**
     * Log a transaction to the database.
     *
     * @param  array<string, mixed>|null  $request
     * @param  array<string, mixed>|null  $response
     */
    protected function logTransaction(
        string $type,
        ?string $reference,
        string $status,
        ?array $request = null,
        ?array $response = null,
        ?string $errorMessage = null,
    ): void {
        if (! config('tcb-cms.logging.enabled', true)) {
            return;
        }

        TcbTransaction::create([
            'type' => $type,
            'reference' => $reference,
            'status' => $status,
            'request' => $request,
            'response' => $response,
            'error_message' => $errorMessage,
        ]);
    }
}
