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
 * Runs the MPI V3 VALIDATE step after the browser completes a challenge.
 * The Cielo VALIDATE payload is intentionally lowercase — camelCase of the
 * other endpoints does NOT apply here (see Cielo doc, §Validate).
 */
interface MpiV3ValidateManagerInterface
{
    /**
     * @param string $cartId
     * @param string $transactionId Value returned by ENROLL Challenge.TransactionId
     * @param string[] $card { CardNumber, ExpirationMonth, ExpirationYear } — string[] because Magento REST rejects `array` here (needs typed array).
     * @return \Braspag\BraspagPagador\Api\Data\MpiV3ValidateResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate($cartId, $transactionId, array $card);
}
