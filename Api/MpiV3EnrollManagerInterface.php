<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Api;

/**
 * Runs the MPI V3 ENROLL step for a given quote.
 *
 * ENROLL is the single call that decides whether the cardholder proceeds
 * frictionless (Authentication returned in response) or through a challenge
 * (status=2 with AcsUrl/Pareq/TransactionId to hand back to the browser).
 */
interface MpiV3EnrollManagerInterface
{
    /**
     * Executes the MPI V3 ENROLL for the cart.
     *
     * The `array` typing on the complex params is intentional: Magento's REST
     * TypeProcessor rejects `mixed[]`, so both browser info and card data are
     * declared as untyped arrays and validated inside the manager.
     *
     * @param string $cartId Masked/numeric quote id
     * @param string[] $browserInfo { userAgent, screenWidth, screenHeight, colorDepth, timeZoneOffset, language, javaEnabled, javascriptEnabled, FingerPrint? }
     * @param string[] $card { PaymentMethod, CardNumber, ExpirationMonth, ExpirationYear, Brand? } — declared as string[] because Magento REST rejects `array`/`mixed[]` here (needs typed array). Numeric fields sent from the browser as strings; the manager casts as needed.
     * @param int $installments Number of installments (1 for cash-like)
     * @param string $paymentMethod `creditcard` / `debitcard` / `voucher` — decides which policy set to read
     * @return \Braspag\BraspagPagador\Api\Data\MpiV3EnrollResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function enroll($cartId, array $browserInfo, array $card, $installments, $paymentMethod);
}
