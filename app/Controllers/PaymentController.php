<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Input;
use App\Kernel\View;
use App\Service\Item as ItemService;
use App\Service\Payment as PaymentService;
use App\Service\UserValidation;

use function App\redirect;

final class PaymentController extends Controller
{
    public const DEFAULT_CURRENCY = 'USD';

    private PaymentService $paymentService;
    private ItemService $itemService;
    private UserValidation $userValidation;

    public function __construct(
        ?PaymentService $paymentService = null,
        ?ItemService $itemService = null,
        ?UserValidation $userValidation = null
    ) {
        parent::__construct();

        $this->paymentService = $paymentService ?? new PaymentService();
        $this->itemService = $itemService ?? new ItemService();
        $this->userValidation = $userValidation ?? new UserValidation();
    }

    public function payment(): void
    {
        $userId = $this->userSessionService->getId();
        if ($userId === null) {
            redirect('/signin');
        }

        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        $doesUserPaymentExist = $this->paymentService->doesPaymentExist($userId);

        if (Input::postExists('payment_submit')) {
            $paypalEmail = Input::postTrimmed('paypal_email');
            $currency = strtoupper(Input::postTrimmed('currency'));

            if ($paypalEmail !== '' && $currency !== '') {
                if (!$this->userValidation->isEmailValid($paypalEmail)) {
                    $viewVariables[View::get_status_message('ERROR')] = 'PayPal email is not valid.';
                } elseif (strlen($currency) !== 3) {
                    $viewVariables[View::get_status_message('ERROR')] = 'Currency must be a 3-letter code (e.g., USD).';
                } elseif ($doesUserPaymentExist) {
                    $this->paymentService->update($userId, $paypalEmail, $currency);
                    $viewVariables[View::get_status_message('SUCCESS')] = 'Payment details saved!';
                } else {
                    $this->paymentService->create([
                        'userId' => $userId,
                        'paypalEmail' => $paypalEmail,
                        'currency' => $currency,
                    ]);
                    $viewVariables[View::get_status_message('SUCCESS')] = 'Payment successfully added.';
                }
            } else {
                $viewVariables[View::get_status_message('ERROR')] = 'All fields are required.';
            }
        }

        $viewVariables['paypalEmail'] = '';
        $viewVariables['currency'] = self::DEFAULT_CURRENCY;
        if ($doesUserPaymentExist) {
            if ($paymentDetails = $this->paymentService->getPaymentDetails($userId)) {
                $viewVariables['paypalEmail'] = (string)($paymentDetails['paypalEmail'] ?? '');
                $viewVariables['currency'] = (string)($paymentDetails['currency'] ?? self::DEFAULT_CURRENCY);
            }
        }

        echo View::render('payment/payment', 'Payment Gateway', $viewVariables);
    }

    public function item(): void
    {
        $userId = $this->userSessionService->getId();
        if ($userId === null) {
            redirect('/signin');
        }

        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
            'isFieldDisabled' => false,
        ];

        $doesItemExist = $this->itemService->hasUserAnItem($userId);

        if (!$this->paymentService->doesPaymentExist($userId)) {
            $viewVariables['isFieldDisabled'] = true;
            $viewVariables[View::get_status_message('ERROR')] = 'You need to set your payment method first.';
        }

        if (Input::postExists('item_submit') && $viewVariables['isFieldDisabled'] !== true) {
            $idName = strtolower(Input::postTrimmed('id_name'));
            $itemName = Input::postTrimmed('item_name');
            $businessName = Input::postTrimmed('business_name');
            $summary = Input::postTrimmed('summary');
            $priceRaw = Input::postTrimmed('price');
            $price = $priceRaw !== '' ? (float)$priceRaw : 0.0;

            if ($idName !== '' && $itemName !== '' && $summary !== '') {
                if (!preg_match('/^[a-z0-9.\\-_]{3,50}$/', $idName)) {
                    $viewVariables[View::get_status_message('ERROR')] = 'ID Name can only contain a-z, 0-9, dot, dash, underscore (min 3 chars).';
                } elseif ($this->doesItemIdNameAlreadyExist($idName, $this->itemService->getFromUserId($userId))) {
                    $viewVariables[View::get_status_message('ERROR')] = sprintf('The "%s" ID Name already exists. Please pick another one.', $idName);
                } else {
                    $inputItemDetails = [
                        'idName' => $idName,
                        'itemName' => $itemName,
                        'businessName' => $businessName,
                        'summary' => $summary,
                        'price' => $price,
                    ];

                    $doesItemExist
                        ? $this->itemService->update($userId, $inputItemDetails)
                        : $this->itemService->create($userId, $inputItemDetails);

                    $viewVariables[View::get_status_message('SUCCESS')] = 'Successfully saved.';
                }
            } else {
                $viewVariables[View::get_status_message('ERROR')] = 'Some required fields are left empty.';
            }
        }

        $viewVariables['idName'] = '';
        $viewVariables['itemName'] = '';
        $viewVariables['businessName'] = '';
        $viewVariables['summary'] = '';
        $viewVariables['price'] = '';
        $viewVariables['shareItemUrl'] = '';
        if ($itemDetails = $this->itemService->getFromUserId($userId)) {
            $viewVariables['idName'] = (string)($itemDetails['idName'] ?? '');
            $viewVariables['itemName'] = (string)($itemDetails['itemName'] ?? '');
            $viewVariables['businessName'] = (string)($itemDetails['businessName'] ?? '');
            $viewVariables['summary'] = (string)($itemDetails['summary'] ?? '');
            $viewVariables['price'] = (string)($itemDetails['price'] ?? '');
            $viewVariables['shareItemUrl'] = $this->itemService->getUserItemUrl((string)($itemDetails['idName'] ?? ''));
        }

        echo View::render('payment/item', 'Edit Item', $viewVariables);
    }

    public function showItem(string $idName): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if ($itemData = $this->itemService->getFromIdName($idName)) {
            $viewVariables += [
                'idName' => (string)($itemData['idName'] ?? ''),
                'itemName' => (string)($itemData['itemName'] ?? ''),
                'businessName' => (string)($itemData['businessName'] ?? ''),
                'summary' => (string)($itemData['summary'] ?? ''),
                'price' => (float)($itemData['price'] ?? 0),
                'currency' => (string)($itemData['currency'] ?? self::DEFAULT_CURRENCY),
                'paymentLink' => $this->paymentService->getPayPalLink($itemData),
                'creatorName' => (string)($itemData['fullname'] ?? ''),
                'creatorEmail' => (string)($itemData['email'] ?? ''),
            ];

            $pageTitle = sprintf('Item %s', (string)($itemData['itemName'] ?? ''));
            echo View::render('payment/show', $pageTitle, $viewVariables);
            return;
        }

        $this->pageNotFound();
    }

    private function doesItemIdNameAlreadyExist(string $idName, array|false $itemDetails): bool
    {
        if (is_array($itemDetails) && ($itemDetails['idName'] ?? '') === $idName) {
            return false;
        }

        return $this->itemService->doesItemIdNameExist($idName);
    }
}
