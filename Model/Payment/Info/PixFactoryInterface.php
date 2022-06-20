<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;

interface PixFactoryInterface
{
    public function create(OrderInterface $order);
}