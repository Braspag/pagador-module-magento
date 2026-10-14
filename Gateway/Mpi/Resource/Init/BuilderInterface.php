<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Init;

use Magento\Quote\Api\Data\CartInterface;

/**
 * Builds INIT requests from a quote — the quote gives us the reserved order
 * number, currency, and grand total. Amount is emitted in minor units.
 */
interface BuilderInterface
{
    /**
     * @param CartInterface $quote
     * @return RequestInterface
     */
    public function build(CartInterface $quote);
}
