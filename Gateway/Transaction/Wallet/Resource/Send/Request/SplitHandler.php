<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Request;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Request;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request\AbstractHandler;
use Braspag\BraspagPagador\Model\SplitManager;

/**
 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */
class SplitHandler extends AbstractHandler implements HandlerInterface
{
    protected $splitManager;
    /**
     * @var
     */
    protected $session;

    public function __construct(
        SplitManager $splitManager,
        Request $request,
        \Magento\Checkout\Model\Session $session
    ) {
        $this->setSplitManager($splitManager);
        $this->setRequest($request);
        $this->setSession($session);
    }

    /**
     * @return Braspag\BraspagPagador\Model\SplitManager
     */
    public function getSplitManager(): SplitManager
    {
        return $this->splitManager;
    }

    /**
     * @param Braspag\BraspagPagador\Model\SplitManager $splitManager
     */
    public function setSplitManager(SplitManager $splitManager)
    {
        $this->splitManager = $splitManager;
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @param $payment
     * @param $request
     * @return $this
     */
    protected function _handle($payment, $request)
    {
        if (!$request->getPaymentSplitRequest()) {
            return $this;
        }

        $request->getPaymentSplitRequest()->prepareSplits();

        $request->getPaymentSplitRequest()->prepareSplitTransactionData();

        $splitData = $request->getPaymentSplitRequest()->getSplits();

        $quote = $this->getSession()->getQuote();

        $this->getSplitManager()->createPaymentSplitByQuote($quote, $splitData);

        return $request;
    }
}