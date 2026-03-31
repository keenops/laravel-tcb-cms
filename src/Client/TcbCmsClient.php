<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Client;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Keenops\LaravelTcbCms\Contracts\TcbCmsClientInterface;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CancelReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceRequest;
use Keenops\LaravelTcbCms\DTOs\CreateReferenceResponse;
use Keenops\LaravelTcbCms\DTOs\ReconciliationRequest;
use Keenops\LaravelTcbCms\DTOs\ReconciliationResponse;
use Keenops\LaravelTcbCms\Enums\ResponseStatus;
use Keenops\LaravelTcbCms\Exceptions\ApiConnectionException;
use Keenops\LaravelTcbCms\Exceptions\InvalidApiKeyException;
use Keenops\LaravelTcbCms\Exceptions\TcbCmsException;

class TcbCmsClient implements TcbCmsClientInterface
{
    public function createReference(CreateReferenceRequest $request): CreateReferenceResponse
    {
        $payload = array_merge($request->toArray(), [
            'partnerCode' => $this->getPartnerCode(),
            'profileID' => $this->getProfileId(),
        ]);

        $response = $this->sendRequest(
            method: 'POST',
            url: $this->getBaseUrl().'/public/api/reference/'.$this->getApiKey(),
            payload: $payload,
        );

        return CreateReferenceResponse::fromArray($response);
    }

    public function cancelReference(CancelReferenceRequest $request): CancelReferenceResponse
    {
        $payload = array_merge($request->toArray(), [
            'partnerCode' => $this->getPartnerCode(),
        ]);

        $response = $this->sendRequest(
            method: 'POST',
            url: $this->getBaseUrl().'/public/api/reference/decline/'.$this->getApiKey(),
            payload: $payload,
        );

        return CancelReferenceResponse::fromArray($response);
    }

    public function reconcile(ReconciliationRequest $request): ReconciliationResponse
    {
        $payload = array_merge($request->toArray(), [
            'partnerCode' => $this->getPartnerCode(),
        ]);

        $response = $this->sendRequest(
            method: 'POST',
            url: $this->getBaseUrl().'/public/api/reconciliation/'.$this->getApiKey(),
            payload: $payload,
        );

        return ReconciliationResponse::fromArray($response);
    }

    /**
     * Send an HTTP request to the TCB CMS API.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws TcbCmsException
     */
    protected function sendRequest(string $method, string $url, array $payload): array
    {
        try {
            $response = $this->buildHttpClient()
                ->{strtolower($method)}($url, $payload);

            return $this->handleResponse($response);
        } catch (ConnectionException $e) {
            throw new ApiConnectionException(
                message: 'Failed to connect to TCB CMS API: '.$e->getMessage(),
                previous: $e,
                context: ['url' => $url, 'payload' => $payload],
            );
        }
    }

    /**
     * Build the HTTP client with proper configuration.
     */
    protected function buildHttpClient(): PendingRequest
    {
        return Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout($this->getTimeout())
            ->retry(
                times: $this->getRetryTimes(),
                sleepMilliseconds: $this->getRetrySleep(),
            );
    }

    /**
     * Handle the API response.
     *
     * @return array<string, mixed>
     *
     * @throws TcbCmsException
     */
    protected function handleResponse(Response $response): array
    {
        $data = $response->json() ?? [];

        if (! $response->successful()) {
            $this->handleErrorResponse($response, $data);
        }

        $status = isset($data['status']) ? (int) $data['status'] : null;

        if ($status === ResponseStatus::ApiKeyError->value) {
            throw new InvalidApiKeyException(
                message: $data['message'] ?? 'Invalid API key',
                context: ['response' => $data],
            );
        }

        return $data;
    }

    /**
     * Handle error responses from the API.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws TcbCmsException
     */
    protected function handleErrorResponse(Response $response, array $data): void
    {
        $message = $data['message'] ?? 'Unknown API error';
        $statusCode = $response->status();

        if ($statusCode === 401 || $statusCode === 403) {
            throw new InvalidApiKeyException(
                message: $message,
                code: $statusCode,
                context: ['response' => $data],
            );
        }

        throw new TcbCmsException(
            message: $message,
            code: $statusCode,
            context: ['response' => $data],
        );
    }

    protected function getApiKey(): string
    {
        return (string) config('tcb-cms.api_key', '');
    }

    protected function getPartnerCode(): string
    {
        return (string) config('tcb-cms.partner_code', '');
    }

    protected function getProfileId(): string
    {
        return (string) config('tcb-cms.profile_id', '');
    }

    protected function getBaseUrl(): string
    {
        return rtrim((string) config('tcb-cms.base_url', 'https://partners.tcbbank.co.tz'), '/');
    }

    protected function getReconciliationBaseUrl(): string
    {
        return rtrim((string) config('tcb-cms.reconciliation_base_url', 'https://partners.tcbbank.co.tz:8444'), '/');
    }

    protected function getTimeout(): int
    {
        return (int) config('tcb-cms.timeout', 30);
    }

    protected function getRetryTimes(): int
    {
        return (int) config('tcb-cms.retry_times', 3);
    }

    protected function getRetrySleep(): int
    {
        return (int) config('tcb-cms.retry_sleep', 100);
    }
}
