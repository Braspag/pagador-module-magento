<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate;

use Magento\Quote\Api\Data\CartInterface;

interface BuilderInterface
{
    /**
     * @param CartInterface $quote
     * @param string        $transactionId
     * @param array         $card
     * @return RequestInterface
     */
    public function build(CartInterface $quote, $transactionId, array $card);
}
