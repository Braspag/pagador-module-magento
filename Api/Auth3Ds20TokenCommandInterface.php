<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Webjump\BraspagPagador\Api;

use Webjump\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface;

/**
 * Interface CommandInterface
 * @package Webjump\BraspagPagador\Api
 */
interface Auth3Ds20TokenCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}
