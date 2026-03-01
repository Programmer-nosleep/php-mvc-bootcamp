<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Input;
use App\Models\Transaction as TransactionModel;
use App\Service\Item as ItemService;
use App\Service\Midtrans as MidtransService;

final class MidtransController extends Controller
{
    private ItemService $itemService;
    private MidtransService $midtransService;
    private TransactionModel $transactionModel;

    public function __construct(
        ?ItemService $itemService = null,
        ?MidtransService $midtransService = null,
        ?TransactionModel $transactionModel = null
    ) {
        parent::__construct();

        $this->itemService = $itemService ?? new ItemService();
        $this->midtransService = $midtransService ?? new MidtransService();
        $this->transactionModel = $transactionModel ?? new TransactionModel();
    }

    public function token(): void
    {
        if (!$this->midtransService->isConfigured()) {
            $this->json(['message' => 'Midtrans is not configured.'], 500);
            return;
        }

        $idName = strtolower(Input::postTrimmed('id_name'));
        if (!preg_match('/^[a-z0-9.\\-_]{3,50}$/', $idName)) {
            $this->json(['message' => 'Invalid item ID.'], 422);
            return;
        }

        $itemData = $this->itemService->getFromIdName($idName);
        if (!$itemData) {
            $this->json(['message' => 'Item not found.'], 404);
            return;
        }

        $currency = strtoupper((string)($itemData['currency'] ?? 'IDR'));
        if ($currency !== 'IDR') {
            $this->json(['message' => 'Midtrans only supports IDR in this demo.'], 422);
            return;
        }

        $amount = (int) round((float)($itemData['price'] ?? 0));
        if ($amount < 1) {
            $this->json(['message' => 'Amount must be greater than 0.'], 422);
            return;
        }

        $orderId = $this->generate_order_id();

        $this->transactionModel->create_new([
            'orderId' => $orderId,
            'idName' => (string)($itemData['idName'] ?? $idName),
            'itemName' => (string)($itemData['itemName'] ?? ''),
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'created',
            'gateway' => 'midtrans',
            'rawResponse' => null,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id' => $idName,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => (string)($itemData['itemName'] ?? 'Support'),
                ],
            ],
        ];

        try {
            $token = $this->midtransService->createSnapToken($params);
            $this->json(['token' => $token, 'orderId' => $orderId]);
        } catch (\Throwable $error) {
            $this->json(['message' => 'Failed to create transaction.'], 500);
        }
    }

    public function notification(): void
    {
        $raw = file_get_contents('php://input') ?: '';
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            $this->json(['message' => 'Invalid payload.'], 400);
            return;
        }

        if (!$this->midtransService->isNotificationSignatureValid($payload)) {
            $this->json(['message' => 'Invalid signature.'], 401);
            return;
        }

        $orderId = (string)($payload['order_id'] ?? '');
        $status = (string)($payload['transaction_status'] ?? '');
        $paymentType = (string)($payload['payment_type'] ?? '');

        if ($orderId === '' || $status === '') {
            $this->json(['message' => 'Missing order_id or transaction_status.'], 422);
            return;
        }

        $this->transactionModel->update_status($orderId, $status, [
            'paymentType' => $paymentType !== '' ? $paymentType : null,
            'rawResponse' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);

        $this->json(['ok' => true]);
    }

    private function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    private function generate_order_id(): string
    {
        $random = bin2hex(random_bytes(4));

        return sprintf('GMAL-%s-%s', date('YmdHis'), $random);
    }
}

