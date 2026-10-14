<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Init;

use Braspag\BraspagPagador\Model\Mpi\CurrencyMap;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\CartInterface;

class Builder implements BuilderInterface
{
    /**
     * @var CurrencyMap
     */
    private $currencyMap;

    /**
     * @param CurrencyMap $currencyMap
     */
    public function __construct(CurrencyMap $currencyMap)
    {
        $this->currencyMap = $currencyMap;
    }

    /**
     * {@inheritdoc}
     */
    public function build(CartInterface $quote)
    {
        $orderNumber = $quote->getReservedOrderId();
        if (!$orderNumber) {
            throw new LocalizedException(
                new Phrase('MPI V3 INIT requires the quote to have a reserved order id.')
            );
        }

        $currencyCode = (string) $quote->getQuoteCurrencyCode();
        $numericCurrency = $this->currencyMap->toNumeric($currencyCode);

        $grandTotal = (float) $quote->getGrandTotal();
        $minorUnits = (string) (int) round($grandTotal * 100);

        return new Request(
            (string) $orderNumber,
            $numericCurrency,
            $minorUnits
        );
    }
}
