<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Void;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;
use Braspag\BraspagPagador\Model\Request\CardTwo;

/**
 * Braspag Transaction CreditCard Capture Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
    protected $request;
    protected $config;
    protected $cardTwo;


    public function __construct(
        RequestInterface $request,
        ConfigInterface $config,
        CardTwo $cardTwo
    ) {
        $this->setRequest($request);
        $this->setConfig($config);
        $this->cardTwo = $cardTwo;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    protected function getConfig()
    {
        return $this->config;
    }

    public function build(array $buildSubject,  $typeCard = '')
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

    
        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();


        $method = $paymentDataObject->getPayment()->getMethodInstance();
        $methodCode = $method->getCode();

      // if ($methodCode == 'braspag_pagador_pix')
      //  return ;

        $paymentId = $paymentDataObject->getPayment()->getAdditionalInformation('payment_token');

        if ($typeCard == 'capture_two_card')
        $paymentId = $this->cardTwo->getData('transactionId');

        if (empty($paymentId)) {
            $paymentId = str_replace(['-capture', '-void', '-refund'], '', $paymentDataObject->getPayment()->getLastTransId());
        }

        $this->getRequest()->setPaymentId($paymentId);

        $this->getRequest()->setOrderAdapter($orderAdapter);

        $this->cardTwo->setData('type_card', $typeCard);

        return $this->getRequest();
    }

    public function hasCardTwo()
    {
        return $this->cardTwo->hasCardTwo();
    }

    public function setCardTowData(array $buildSubject)
    {
        $payment = $buildSubject['payment']->getPayment();

        $this->cardTwo->setAdditionalData(
            [
                'cc_amount_card2' => $payment->getAdditionalInformation('two_card_total_amount'),
                'two_card_payment_id' => $payment->getAdditionalInformation('two_card_paymentId'),
                'two_card_last_4' => $payment->getAdditionalInformation('two_card_last_4')
            ]
        )->execute();
        
        return  $this->cardTwo;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }
}