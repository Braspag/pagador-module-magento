<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Data\Auth3DS20CartInformationInterface;

/**
 * Interface Auth3DS20GetCartInterface
 *
 * @package Webjump\BraspagPagador\Api
 */
interface Auth3DS20GetCartInterface
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface[]
     */
    public function getCartData(\Magento\Quote\Api\Data\CartInterface $quote): array;
}
