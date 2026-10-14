<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Braspag\BraspagPagador\Gateway\Mpi\Resource\Enroll\Request as EnrollRequest;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Builds the full MPI V3 ENROLL payload from a quote and the browser-side
 * data (BrowserInfo, FingerPrint, card BIN/expiration).
 *
 * This is where the responsibility that lived in the V2 `bpmpi.js:113-160`
 * (populating ~50 hidden inputs from the quote and address) moves to the
 * backend. Doing it server-side lets us:
 *  - avoid trusting client-mutated fields for compliance-relevant data,
 *  - use the actual quote billing/shipping and cart items authoritative,
 *  - keep MerchantDefinedData under admin control.
 */
class PayloadBuilder
{
    /**
     * TransactionMode "S" = ecommerce.
     */
    const TRANSACTION_MODE = 'S';

    /**
     * DeviceInfo.Channel — Cielo doc lists "Browser", "SDK", "3RI" (linha
     * 958/1039/1520 of the doc). This is NOT the same as OrderChannel;
     * previous "S" was wrong and got the payload rejected.
     */
    const DEVICE_CHANNEL = 'Browser';

    /**
     * ProductCode "PHY" = physical goods.
     */
    const PRODUCT_CODE = 'PHY';

    /**
     * ChallengeWindowSize — doc expects the dimension string, NOT a numeric
     * code. Accepted values: "250x400", "390x400", "500x600" (default),
     * "600x400", "FullScreen".
     */
    const CHALLENGE_WINDOW_SIZE = '500x600';

    /**
     * Fingerprint provider name Cielo expects exactly this string.
     */
    const FINGERPRINT_PROVIDER = 'cardinal';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param UrlInterface         $urlBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param CartInterface $quote
     * @param array         $browserInfo
     * @param array         $card
     * @param int           $installments
     * @param string        $paymentMethod creditcard / debitcard / voucher
     * @return EnrollRequest
     */
    public function build(
        CartInterface $quote,
        array $browserInfo,
        array $card,
        $installments,
        $paymentMethod
    ) {
        $billing = $quote->getBillingAddress();
        $shipping = $quote->getShippingAddress();
        $sameAsBillTo = $this->addressesMatch($billing, $shipping);

        $payload = [
            'TransactionMode'      => self::TRANSACTION_MODE,
            'MerchantUrl'          => $this->urlBuilder->getUrl('/'),
            'OrderNumber'          => (string) $quote->getReservedOrderId(),
            'Currency'             => (string) $quote->getQuoteCurrencyCode(),
            // TotalAmount is numeric per Cielo doc — do NOT cast to string.
            'TotalAmount'          => (int) round(((float) $quote->getGrandTotal()) * 100),
            'Installments'         => (int) $installments,
            'ShipToSameAsBillTo'   => $sameAsBillTo,
            'AuthNotifyOnly'       => $this->isMasterCardNotifyOnly($paymentMethod),
            'ChallengeWindowSize'  => self::CHALLENGE_WINDOW_SIZE,
            'Card'                 => $this->buildCard($card, $paymentMethod),
            'BillTo'               => $this->buildBillTo($quote, $billing),
            'ShipTo'               => $this->buildShipTo($quote, $shipping ?: $billing),
            'CartItems'            => $this->buildCartItems($quote),
            'DeviceInfo'           => $this->buildDeviceInfo($quote, $browserInfo),
            'Order'                => $this->buildOrder(),
            'UserAccount'          => $this->buildUserAccount($quote),
            'BrowserInfo'          => $this->buildBrowserInfo($browserInfo),
        ];

        $mdd = $this->buildMerchantDefinedData($paymentMethod);
        if (!empty($mdd)) {
            $payload['MerchantDefinedData'] = $mdd;
        }

        return new EnrollRequest($payload);
    }

    /**
     * @param array  $card
     * @param string $paymentMethod creditcard / debitcard / voucher
     * @return array
     */
    private function buildCard(array $card, $paymentMethod)
    {
        // Cielo doc expects lowercase "credit" / "debit" (see linha 930, 1000
        // and every ENROLL/VALIDATE sample). Values like "CreditCard" got the
        // payload rejected with Reason.Code 101.
        $method = isset($card['PaymentMethod']) && $card['PaymentMethod'] !== ''
            ? strtolower((string) $card['PaymentMethod'])
            : ($paymentMethod === 'debitcard' ? 'debit' : 'credit');
        if ($method === 'creditcard') { $method = 'credit'; }
        if ($method === 'debitcard') { $method = 'debit'; }

        return [
            'PaymentMethod'   => $method,
            'CardNumber'      => isset($card['CardNumber']) ? (string) $card['CardNumber'] : '',
            'ExpirationMonth' => isset($card['ExpirationMonth']) ? (int) $card['ExpirationMonth'] : 0,
            'ExpirationYear'  => isset($card['ExpirationYear']) ? (int) $card['ExpirationYear'] : 0,
        ];
    }

    /**
     * @param CartInterface $quote
     * @param \Magento\Quote\Api\Data\AddressInterface|null $address
     * @return array
     */
    private function buildBillTo(CartInterface $quote, $address)
    {
        if (!$address) {
            return [];
        }
        $street = (array) $address->getStreet();
        return [
            'CustomerId'  => (string) ($quote->getCustomerTaxvat() ?: ($address->getVatId() ?: '')),
            'Name'        => trim($address->getFirstname() . ' ' . $address->getLastname()),
            'PhoneNumber' => (string) $address->getTelephone(),
            'Email'       => (string) $quote->getCustomerEmail(),
            'Street1'     => $this->joinStreet($street),
            'Street2'     => isset($street[2]) ? (string) $street[2] : '',
            'City'        => (string) $address->getCity(),
            'State'       => (string) ($address->getRegionCode() ?: $address->getRegion()),
            'ZipCode'     => preg_replace('/[^A-Za-z0-9]+/', '', (string) $address->getPostcode()),
            'Country'     => (string) $address->getCountryId(),
        ];
    }

    /**
     * @param CartInterface $quote
     * @param \Magento\Quote\Api\Data\AddressInterface|null $address
     * @return array
     */
    private function buildShipTo(CartInterface $quote, $address)
    {
        if (!$address) {
            return [];
        }
        $street = (array) $address->getStreet();
        return [
            // Doc field is "Name" (linha 1018, 1503) — "Addressee" was wrong
            // and caused ShipTo.Name to be missing when ShipToSameAsBillTo=false.
            'Name'        => trim($address->getFirstname() . ' ' . $address->getLastname()),
            'PhoneNumber' => (string) $address->getTelephone(),
            'Email'       => (string) $quote->getCustomerEmail(),
            'Street1'     => $this->joinStreet($street),
            'Street2'     => isset($street[2]) ? (string) $street[2] : '',
            'City'        => (string) $address->getCity(),
            'State'       => (string) ($address->getRegionCode() ?: $address->getRegion()),
            'ZipCode'     => preg_replace('/[^A-Za-z0-9]+/', '', (string) $address->getPostcode()),
            'Country'     => (string) $address->getCountryId(),
        ];
    }

    /**
     * @param CartInterface $quote
     * @return array
     */
    private function buildCartItems(CartInterface $quote)
    {
        $items = [];
        foreach ($quote->getAllVisibleItems() ?: [] as $item) {
            $items[] = [
                'Name'        => (string) $item->getName(),
                'Sku'         => (string) $item->getSku(),
                'Description' => (string) ($item->getDescription() ?: $item->getName()),
                'Quantity'    => (int) $item->getQty(),
                'UnitPrice'   => (int) round(((float) $item->getPrice()) * 100),
            ];
        }
        return $items;
    }

    /**
     * @param CartInterface $quote
     * @param array         $browserInfo
     * @return array
     */
    private function buildDeviceInfo(CartInterface $quote, array $browserInfo)
    {
        $device = [
            'IpAddress' => (string) $quote->getRemoteIp(),
            'Channel'   => self::DEVICE_CHANNEL,
        ];

        // Fingerprint fallback per decision #7: omit the Devices field entirely
        // when there's no fingerprint — Cielo accepts absence but rejects a
        // malformed object.
        if (!empty($browserInfo['FingerPrint'])) {
            $device['Devices'] = [[
                'FingerPrint' => (string) $browserInfo['FingerPrint'],
                'Provider'    => self::FINGERPRINT_PROVIDER,
            ]];
        }

        return $device;
    }

    /**
     * @return array
     */
    private function buildOrder()
    {
        return [
            'ProductCode'              => self::PRODUCT_CODE,
            'CountLast24Hours'         => 0,
            'CountLast6Months'         => 0,
            'CountLast1Year'           => 0,
            'CardAttemptsLast24Hours'  => 0,
        ];
    }

    /**
     * @param CartInterface $quote
     * @return array
     */
    private function buildUserAccount(CartInterface $quote)
    {
        $customer = $quote->getCustomer();
        $isGuest  = !$quote->getCustomerId();

        return [
            'Guest'                => $isGuest,
            'CreatedDate'          => $customer && $customer->getCreatedAt() ? substr((string) $customer->getCreatedAt(), 0, 10) : '',
            'ChangedDate'          => $customer && $customer->getUpdatedAt() ? substr((string) $customer->getUpdatedAt(), 0, 10) : '',
            'PasswordChangedDate'  => '',
            // Doc linha 1534: 01=no auth, 02=store login, 03=federated id,
            // 04=FIDO. Guests were never authenticated → 01. Logged customers
            // signed into the store → 02.
            'AuthenticationMethod' => $isGuest ? '01' : '02',
        ];
    }

    /**
     * Builds the BrowserInfo section.
     *
     * IMPORTANT: `MPIHelpers.getBrowserInfo()` returns keys in **camelCase**
     * (`userAgent`, `screenWidth`, `screenHeight`, `colorDepth`,
     * `timeZoneOffset`, `language`, `javaEnabled`, `javascriptEnabled` —
     * lowercase "s" in "javascript"). Cielo expects **PascalCase** keys with
     * **string** values, and Java*Enabled as `"Y"`/`"N"` strings (not bools).
     * Reading PascalCase from the input would leave every field empty and
     * fail ENROLL with Reason.Code 101.
     *
     * @param array $browserInfo Raw dictionary as returned by MPIHelpers
     * @return array
     */
    private function buildBrowserInfo(array $browserInfo)
    {
        $read = function ($key) use ($browserInfo) {
            return array_key_exists($key, $browserInfo) ? $browserInfo[$key] : null;
        };

        return [
            'UserAgent'         => (string) ($read('userAgent') ?? $read('UserAgent') ?? ''),
            'ScreenWidth'       => (string) ($read('screenWidth') ?? $read('ScreenWidth') ?? ''),
            'ScreenHeight'      => (string) ($read('screenHeight') ?? $read('ScreenHeight') ?? ''),
            'ColorDepth'        => (string) ($read('colorDepth') ?? $read('ColorDepth') ?? ''),
            'TimeZoneOffset'    => (string) ($read('timeZoneOffset') ?? $read('TimeZoneOffset') ?? ''),
            'Language'          => (string) ($read('language') ?? $read('Language') ?? ''),
            'JavaEnabled'       => $this->toYn($read('javaEnabled') ?? $read('JavaEnabled')),
            'JavaScriptEnabled' => $this->toYn(
                $read('javascriptEnabled') ?? $read('JavaScriptEnabled') ?? $read('javaScriptEnabled') ?? 'Y'
            ),
        ];
    }

    /**
     * Normalizes truthy/falsy inputs to the "Y"/"N" strings Cielo expects.
     *
     * @param mixed $value
     * @return string
     */
    private function toYn($value)
    {
        if ($value === null || $value === '') {
            return 'N';
        }
        if (is_string($value)) {
            $up = strtoupper($value);
            if ($up === 'Y' || $up === 'N') {
                return $up;
            }
            return in_array($up, ['1', 'TRUE'], true) ? 'Y' : 'N';
        }
        return $value ? 'Y' : 'N';
    }

    /**
     * MDD 1..3 are the ones the Cielo doc actually cites; MDD 4/5 are kept
     * in admin (decision #4) but NOT forwarded here. If Cielo starts
     * accepting them, this method is the single point of change.
     *
     * @param string $paymentMethod creditcard / debitcard / voucher
     * @return array
     */
    private function buildMerchantDefinedData($paymentMethod)
    {
        $mdd = [];
        for ($i = 1; $i <= 3; $i++) {
            $path = sprintf(
                'payment/braspag_pagador_%s/authentication_3ds_20_mdd%d',
                $paymentMethod,
                $i
            );
            $value = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
            if ($value !== null && $value !== '') {
                $mdd[$i] = (string) $value;
            }
        }
        return $mdd;
    }

    /**
     * @param string $paymentMethod
     * @return bool
     */
    private function isMasterCardNotifyOnly($paymentMethod)
    {
        return (bool) $this->scopeConfig->getValue(
            sprintf('payment/braspag_pagador_%s/authentication_3ds_20_mastercard_notify_only', $paymentMethod),
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Joins the first two street lines with a comma only when the second is
     * present — avoids the trailing "Rua X, " when a store has 1-line streets.
     *
     * @param array $street
     * @return string
     */
    private function joinStreet(array $street)
    {
        $first = isset($street[0]) ? trim((string) $street[0]) : '';
        $second = isset($street[1]) ? trim((string) $street[1]) : '';
        if ($first === '' && $second === '') {
            return '';
        }
        return $second === '' ? $first : $first . ', ' . $second;
    }

    /**
     * @param \Magento\Quote\Api\Data\AddressInterface|null $billing
     * @param \Magento\Quote\Api\Data\AddressInterface|null $shipping
     * @return bool
     */
    private function addressesMatch($billing, $shipping)
    {
        if (!$billing || !$shipping) {
            return true;
        }
        $normalize = function ($address) {
            return sprintf(
                '%s|%s|%s|%s|%s',
                implode('|', (array) $address->getStreet()),
                $address->getCity(),
                $address->getPostcode(),
                $address->getRegionCode(),
                $address->getCountryId()
            );
        };
        return $normalize($billing) === $normalize($shipping);
    }
}
