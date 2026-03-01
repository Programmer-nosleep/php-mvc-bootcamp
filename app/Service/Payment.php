<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Payment as PaymentModel;

final class Payment
{
    private const PAYPAL_PAYMENT_URL = 'https://www.paypal.com/cgi-bin/webscr';

    private PaymentModel $paymentModel;

    public function __construct(?PaymentModel $paymentModel = null)
    {
        $this->paymentModel = $paymentModel ?? new PaymentModel();
    }

    public function create(array $paymentDetails): string|bool
    {
        return $this->paymentModel->insert($paymentDetails);
    }

    public function update(int|string $userId, string $paypalEmail, string $currency): bool
    {
        return $this->paymentModel->update($userId, $paypalEmail, $currency);
    }

    public function doesPaymentExist(int|string $userId): bool
    {
        return $this->paymentModel->does_details_exist($userId);
    }

    public function getPaymentDetails(int|string $userId): array|false
    {
        return $this->paymentModel->get_details($userId);
    }

    public function getPayPalLink(array $itemData): string
    {
        $queries = [
            'cmd' => '_xclick',
            'business' => $itemData['paypalEmail'] ?? '',
            'item_name' => $itemData['itemName'] ?? '',
            'amount' => number_format((float)($itemData['price'] ?? 0), 2, '.', ''),
            'currency_code' => $itemData['currency'] ?? 'USD',
        ];

        return self::PAYPAL_PAYMENT_URL . '?' . http_build_query($queries);
    }
}

