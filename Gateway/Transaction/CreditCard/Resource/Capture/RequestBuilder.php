<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Braspag\BraspagPagador\Model\Source\PaymentSplitType;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Braspag\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Braspag\BraspagPagador\Model\Request\CardTwo;

/**
 * Braspag Transaction CreditCard Capture Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
    protected $request;

    protected $cardTwo;

    public function __construct(
        RequestInterface $request,
        RequestPaymentSplitLibInterface $requestPaymentSplit,
        ConfigInterface $config,
        CardTwo $cardTwo
    ) {
        $this->setRequest($request);
        $this->setPaymentSplitRequest($requestPaymentSplit);
        $this->setConfig($config);
        $this->cardTwo = $cardTwo;
    }

    protected function setPaymentSplitRequest(RequestPaymentSplitLibInterface $requestPaymentSplit)
    {
        $this->requestPaymentSplit = $requestPaymentSplit;
        return $this;
    }

    protected function getRequestPaymentSplit()
    {
        return $this->requestPaymentSplit;
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

    public function build(array $buildSubject, $typeCard = '')
    {

        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();

        $paymentId = $paymentDataObject->getPayment()->getAdditionalInformation('payment_token');
        

        if (empty($paymentId)) {
            $paymentId = str_replace(['-capture', '-void', '-refund'], '', $paymentDataObject->getPayment()->getLastTransId());
        }

        if ($typeCard == 'capture_two_card')
        $paymentId = $this->cardTwo->getData('transactionId');

        if ($this->getConfig()->isPaymentSplitActive()) {
            $this->getRequestPaymentSplit()->setConfig($this->getConfig());
            $this->getRequestPaymentSplit()->setOrder($paymentDataObject->getPayment()->getOrder());
            $this->getRequest()->setPaymentSplitRequest($this->getRequestPaymentSplit());
        }

        $this->getRequest()->setPaymentId($paymentId);
        $this->getRequest()->setOrderAdapter($orderAdapter);

        if (isset($buildSubject['amount']) && !empty($buildSubject['amount'])) {
            $this->getRequest()->setCaptureAmount($buildSubject['amount']);
        }

        $this->cardTwo->setData('type_card', $typeCard);

        return $this->getRequest();
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

    public function setResponse($response)
    {
        return $this->cardTwo->setData('response', $response);
    }
}