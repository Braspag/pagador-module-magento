<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Pix\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Pix\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\RequestFactory;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;

/**
 * Braspag Transaction Pix Send Request Builder
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */
class RequestBuilder implements BuilderInterface
{
    protected $requestFactory;
    protected $orderRepository;

    /**
     * RequestBuilder constructor.
     * @param \Webjump\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\RequestFactory $requestFactory
     * @param RequestPaymentSplitLibInterface $requestPaymentSplit
     * @param ConfigInterface $config
     */
    public function __construct(
        RequestFactory $requestFactory,
        RequestPaymentSplitLibInterface $requestPaymentSplit,
        ConfigInterface $config
    ) {
        $this->setRequestFactory($requestFactory);
        $this->setPaymentSplitRequest($requestPaymentSplit);
        $this->setConfig($config);
    }

    /**
     * @param array $buildSubject
     * @return array|mixed
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $request = $this->getRequestFactory()->create();

        $paymentDataObject = $buildSubject['payment'];
        $paymentData = $paymentDataObject->getPayment();

        /** @var OrderAdapter $orderAdapter */
        $orderAdapter = $paymentDataObject->getOrder();

        if (
            $this->getConfig()->isPaymentSplitActive()
            && $this->getConfig()->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL
        ) {
            $this->getRequestPaymentSplit()->setConfig($this->getConfig());
            $request->setPaymentSplitRequest($this->getRequestPaymentSplit());
        }

        $request->setOrderAdapter($orderAdapter);
        $request->setPaymentData($paymentData);

        return $request;
    }

    /**
     * @param \Webjump\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\RequestFactory $requestFactory
     * @return $this
     */
    protected function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param RequestPaymentSplitLibInterface $requestPaymentSplit
     * @return $this
     */
    protected function setPaymentSplitRequest(RequestPaymentSplitLibInterface $requestPaymentSplit)
    {
        $this->requestPaymentSplit = $requestPaymentSplit;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getRequestPaymentSplit()
    {
        return $this->requestPaymentSplit;
    }

    /**
     * @param ConfigInterface $config
     * @return $this
     */
    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        return $this->config;
    }
}