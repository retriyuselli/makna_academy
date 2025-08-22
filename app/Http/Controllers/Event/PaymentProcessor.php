<?php

namespace App\Http\Controllers\Event;

use App\Models\Payment;

class PaymentProcessor
{
    public function processBankTransfer(Payment $payment): array
    {
        return [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT Makna Academy',
            'instructions' => [
                'Transfer the exact amount to avoid payment verification issues',
                'Include your invoice number in the transfer description',
                'Keep your payment proof for verification'
            ],
            'payment_code' => strtoupper(uniqid('BT'))
        ];
    }

    public function processCreditCard(Payment $payment): array
    {
        return [
            'gateway' => 'Midtrans',
            'payment_token' => strtoupper(uniqid('CC')),
            'redirect_url' => 'https://payment-gateway.example/pay/' . $payment->invoice_number
        ];
    }

    public function processEWallet(Payment $payment): array
    {
        return [
            'provider' => 'GoPay',
            'payment_code' => strtoupper(uniqid('EW')),
            'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?data=' . $payment->invoice_number,
            'deeplink_url' => 'gopay://payment?invoice=' . $payment->invoice_number
        ];
    }
}
