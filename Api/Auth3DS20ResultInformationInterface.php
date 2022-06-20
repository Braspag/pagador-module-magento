<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Braspag\BraspagPagador\Api;

use Braspag\BraspagPagador\Api\Data\Auth3DS20InformationInterface;

/**
 * Interface Auth3DS20ResultInformationInterface
 *
 * @package Braspag\BraspagPagador\Api
 */
interface Auth3DS20ResultInformationInterface
{
    /**
     * @param int $cartId
     * @return \Braspag\BraspagPagador\Api\Data\Auth3DS20InformationInterface
     */
    public function getInformation(int $cartId): Auth3DS20InformationInterface;
}