<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Braspag\BraspagPagador\Api;

use Braspag\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface;

/**
 * Interface CommandInterface
 * @package Braspag\BraspagPagador\Api
 */
interface OAuth2TokenCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}