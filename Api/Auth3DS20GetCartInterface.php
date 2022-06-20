<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Braspag\BraspagPagador\Api;

use Braspag\BraspagPagador\Data\Auth3DS20CartInformationInterface;

/**
 * Interface Auth3DS20GetCartInterface
 *
 * @package Braspag\BraspagPagador\Api
 */
interface Auth3DS20GetCartInterface
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Braspag\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface[]
     */
    public function getCartData(\Magento\Quote\Api\Data\CartInterface $quote): array;
}