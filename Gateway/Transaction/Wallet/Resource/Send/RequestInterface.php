<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Model\InfoInterface;

/**
 * Braspag Transaction Pix Send Request Interface
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */
interface RequestInterface
{
    public function setOrderAdapter(OrderAdapterInterface $order);
}