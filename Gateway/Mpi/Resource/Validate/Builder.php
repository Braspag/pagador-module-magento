<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\CartInterface;

class Builder implements BuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(CartInterface $quote, $transactionId, array $card)
    {
        if (empty($transactionId)) {
            throw new LocalizedException(
                new Phrase('MPI V3 VALIDATE requires the ENROLL transactionId.')
            );
        }

        $orderNumber = (string) $quote->getReservedOrderId();
        $currency    = (string) $quote->getQuoteCurrencyCode();
        $totalAmount = (string) (int) round(((float) $quote->getGrandTotal()) * 100);

        // Cielo expects lowercase keys on this payload.
        $normalizedCard = [
            'cardnumber'      => isset($card['CardNumber']) ? (string) $card['CardNumber'] : (string) ($card['cardnumber'] ?? ''),
            'expirationmonth' => (int) ($card['ExpirationMonth'] ?? $card['expirationmonth'] ?? 0),
            'expirationyear'  => (int) ($card['ExpirationYear'] ?? $card['expirationyear'] ?? 0),
        ];

        return new Request(
            $orderNumber,
            $currency,
            $totalAmount,
            $normalizedCard,
            (string) $transactionId
        );
    }
}
