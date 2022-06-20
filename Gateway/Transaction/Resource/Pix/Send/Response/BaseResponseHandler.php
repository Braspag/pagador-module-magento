<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

class BaseResponseHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param $response
     * @return mixed|void
     */
    protected function _handle($payment, $response)
    {
        if (!isset($response["error"])) {
            $payment->setData('cc_trans_id', $response['id']);
            $payment->setTransactionId($response['id']);
            $payment->setIsTransactionClosed(false);
        } else {
            throw new \Exception($response["error"]["message"]);
        }

        return $this;
    }
}