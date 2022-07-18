<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Braspag\BraspagPagador\Api;

use Braspag\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface;

/**
 * Interface CommandInterface
 * @package Braspag\BraspagPagador\Api
 */
interface Auth3Ds20TokenCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}