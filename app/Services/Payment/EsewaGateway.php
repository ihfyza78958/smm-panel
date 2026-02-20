<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EsewaGateway implements PaymentInterface
{
    protected string $merchantCode;
    protected string $verifyUrl;
    protected string $payUrl;

    public function __construct()
    {
        $this->merchantCode = config('gateways.esewa.merchant_id', 'EPAYTEST');
        $this->verifyUrl = config('gateways.esewa.verification_url', 'https://rc.esewa.com.np/mobile/transaction');
        $this->payUrl = config('gateways.esewa.url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');
    }

    public function initiate(float $amount, string $transactionId): array
    {
        // eSewa requires a form POST
        return [
            'method' => 'POST',
            'url' => $this->payUrl,
            'fields' => [
                'amt' => $amount,
                'pdc' => 0,
                'psc' => 0,
                'txAmt' => 0,
                'tAmt' => $amount,
                'pid' => $transactionId,
                'scd' => $this->merchantCode,
                'su' => route('payment.success', ['gateway' => 'esewa']),
                'fu' => route('payment.failure', ['gateway' => 'esewa']),
            ]
        ];
    }

    public function verify(Request $request): array
    {
        $oid = $request->input('oid');
        $amt = $request->input('amt');
        $refId = $request->input('refId');

        $response = Http::post($this->verifyUrl, [
            'amt' => $amt,
            'scd' => $this->merchantCode,
            'pid' => $oid,
            'rid' => $refId,
        ]);

        if (str_contains($response->body(), 'Success')) {
            return [
                'status' => true,
                'amount' => (float) $amt,
                'transaction_id' => $oid,
                'gateway_ref_id' => $refId,
            ];
        }

        return ['status' => false, 'message' => 'Verification failed'];
    }

    public function getName(): string
    {
        return 'esewa';
    }
}
