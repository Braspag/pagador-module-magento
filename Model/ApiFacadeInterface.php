<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model;

use Zoop\Api;
use Zoop\Api\RequestInterface;

interface ApiFacadeInterface
{
    /**
     * @param RequestInterface $request
     * @return array
     */
    public function submitBankslip(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function captureTransaction(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function associateBuyer(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function createTransaction(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function getTransaction(RequestInterface $request);

    /**
     * @return Api
     */
    public function getApi();
}