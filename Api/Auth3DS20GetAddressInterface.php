<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface;

/**
 * Interface Auth3DS20GetAddressInterface
 *
 * @package Webjump\BraspagPagador\Api
 */
interface Auth3DS20GetAddressInterface
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return Auth3DS20AddressInformationInterface
     */
    public function getAddressData(\Magento\Quote\Api\Data\CartInterface $quote): Auth3DS20AddressInformationInterface;
}
