<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request;

/**
 * Interface HandlerInterface
 * @package Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request
 */
interface HandlerInterface
{
    /**
     * Handles request
     *
     * @param array $handlingSubject
     * @param array $request
     * @return void
     */
    public function handle(array $handlingSubject, array $request);
}