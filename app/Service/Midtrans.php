<?php
declare(strict_types=1);

namespace App\Service;

use Midtrans\Config;
use Midtrans\Snap;

final class Midtrans
{
    public function isConfigured(): bool
    {
        return !empty($_ENV['MIDTRANS_SERVER_KEY'] ?? '') && !empty($_ENV['MIDTRANS_CLIENT_KEY'] ?? '');
    }

    public function createSnapToken(array $params): string
    {
        $this->configure();

        return Snap::getSnapToken($params);
    }

    public function isNotificationSignatureValid(array $payload): bool
    {
        $serverKey = (string)($_ENV['MIDTRANS_SERVER_KEY'] ?? '');
        if ($serverKey === '') {
            return false;
        }

        $orderId = (string)($payload['order_id'] ?? '');
        $statusCode = (string)($payload['status_code'] ?? '');
        $grossAmount = (string)($payload['gross_amount'] ?? '');
        $signatureKey = (string)($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            return false;
        }

        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($computed, $signatureKey);
    }

    private function configure(): void
    {
        Config::$serverKey = (string)($_ENV['MIDTRANS_SERVER_KEY'] ?? '');
        Config::$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false', FILTER_VALIDATE_BOOL);
        Config::$isSanitized = filter_var($_ENV['MIDTRANS_IS_SANITIZED'] ?? 'true', FILTER_VALIDATE_BOOL);
        Config::$is3ds = filter_var($_ENV['MIDTRANS_IS_3DS'] ?? 'true', FILTER_VALIDATE_BOOL);
    }
}

