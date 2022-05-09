<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Webjump\BraspagPagador\Api;

use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\CreateSubordinate\RequestInterface;

/**
 * Interface CommandInterface
 * @package Webjump\BraspagPagador\Api
 */
interface PaymentSplitCreateSubordinateCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}
