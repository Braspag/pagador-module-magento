<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Braspag\BraspagPagador\Api;

use Braspag\Braspag\Pagador\Transaction\Api\PaymentSplit\CreateSubordinate\RequestInterface;

/**
 * Interface CommandInterface
 * @package Braspag\BraspagPagador\Api
 */
interface PaymentSplitCreateSubordinateCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}