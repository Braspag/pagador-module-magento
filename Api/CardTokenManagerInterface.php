<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Api;

use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

interface CardTokenManagerInterface
{
    public function registerCardToken($customerId, $paymentMethod, $response);

    /**
     * @param $cardToken
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function disable(CardTokenInterface  $cardToken);
}
