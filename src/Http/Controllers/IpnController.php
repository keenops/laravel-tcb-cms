<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Keenops\LaravelTcbCms\DTOs\IpnPayload;
use Keenops\LaravelTcbCms\Events\PaymentReceived;
use Keenops\LaravelTcbCms\Models\TcbTransaction;

class IpnController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (! $this->isIpAllowed($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized IP address',
            ], 403);
        }

        $data = $request->all();
        $payload = IpnPayload::fromArray($data);

        $this->logTransaction($payload, $data);

        event(new PaymentReceived($payload));

        return response()->json([
            'status' => 'success',
            'message' => 'Payment notification received',
        ]);
    }

    /**
     * Check if the request IP is allowed.
     */
    protected function isIpAllowed(Request $request): bool
    {
        if (! config('tcb-cms.verify_ip', false)) {
            return true;
        }

        $allowedIps = config('tcb-cms.allowed_ips', []);

        if (empty($allowedIps)) {
            return true;
        }

        return in_array($request->ip(), $allowedIps, true);
    }

    /**
     * Log the IPN transaction.
     *
     * @param  array<string, mixed>  $rawData
     */
    protected function logTransaction(IpnPayload $payload, array $rawData): void
    {
        if (! config('tcb-cms.logging.enabled', true)) {
            return;
        }

        TcbTransaction::create([
            'type' => 'ipn',
            'reference' => $payload->reference,
            'status' => 'success',
            'request' => $rawData,
            'response' => ['acknowledged' => true],
            'error_message' => null,
        ]);
    }
}
