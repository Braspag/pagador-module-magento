<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Webjump\BraspagPagador\Api;

use Webjump\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface;

/**
 * Interface CommandInterface
 * @package Webjump\BraspagPagador\Api
 */
interface OAuth2TokenCommandInterface
{
    /**
     * @return mixed
     */
    public function execute(RequestInterface $request);
}
