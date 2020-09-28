<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface;

/**
 * Interface Auth3DS20UserAccountInterface
 *
 * @package Webjump\BraspagPagador\Api
 */
interface Auth3DS20UserAccountInterface
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return Auth3DS20UserAccountInformationInterface
     */
    public function getUserAccount(\Magento\Quote\Api\Data\CartInterface $quote): Auth3DS20UserAccountInformationInterface;
}
