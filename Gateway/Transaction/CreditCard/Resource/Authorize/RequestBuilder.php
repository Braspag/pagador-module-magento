<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;
/**
 * Braspag Transaction Billet Send Request Builder
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
    protected $requestAntiFraud;
    protected $requestAvs;
    protected $orderRepository;
    protected $config;

    public function __construct (
        BaseRequestInterface $request,
        RequestAntiFraudLibInterface $requestAntiFraud,
        RequestAvsLibInterface $requestAvs,
        ConfigInterface $config
    ) {
        $this->setRequest($request);
        $this->setAntiFraudRequest($requestAntiFraud);
        $this->setAvsRequest($requestAvs);
        $this->setConfig($config);
    }

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();
        $paymentData = $paymentDataObject->getPayment();

        if($this->getConfig()->hasAntiFraud()) {
            $this->getRequestAntiFraud()->setOrderAdapter($orderAdapter);
            $this->getRequestAntiFraud()->setPaymentData($paymentData);
            $this->getRequest()->setAntiFraudRequest($this->getRequestAntiFraud());
        }

        if($this->getConfig()->hasAvs()) {
            $this->getRequestAvs()->setOrderAdapter($orderAdapter);
            $this->getRequestAvs()->setPaymentData($paymentData);
            $this->getRequest()->setAvsRequest($this->getRequestAvs());
        }

        $this->getRequest()->setOrderAdapter($orderAdapter);
        $this->getRequest()->setPaymentData($paymentData);

        return $this->getRequest();
    }

    protected function setRequest(BaseRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function setAntiFraudRequest(RequestAntiFraudLibInterface $requestAntiFraud)
    {
        $this->requestAntiFraud = $requestAntiFraud;
        return $this;
    }

    protected function getRequestAntiFraud()
    {
        return $this->requestAntiFraud;
    }

    public function setAvsRequest(RequestAvsLibInterface $requestAvs)
    {
        $this->requestAvs = $requestAvs;
        return $this;
    }

    public function getRequestAvs()
    {
        return $this->requestAvs;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    protected  function getConfig()
    {
        return $this->config;
    }
}
