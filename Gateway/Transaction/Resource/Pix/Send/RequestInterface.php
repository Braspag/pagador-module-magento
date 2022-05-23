<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Resource\Pix\Send;

interface RequestInterface
{
    public function prepareRequest($quote, $orderAdapter);
}