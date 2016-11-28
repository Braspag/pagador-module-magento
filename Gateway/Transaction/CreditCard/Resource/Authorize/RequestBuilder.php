<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;

use Magento\Store\Model\ScopeInterface;
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
    protected $orderRepository;
    protected $scopeConfig;

    /**
     * @param BaseRequestInterface $request
     * @param RequestAntiFraudLibInterface $requestAntiFraud
     * @param ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        BaseRequestInterface $request,
        RequestAntiFraudLibInterface $requestAntiFraud,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->setRequest($request);
        $this->setAntiFraudRequest($requestAntiFraud);
        $this->setScopeConfig($scopeConfigInterface);
    }

    /**
     * @param array $buildSubject
     * @return RequestInterface
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();
        $paymentData = $paymentDataObject->getPayment();

        if($this->hasAntiFraud()) {
            $this->getRequestAntiFraud()->setOrderAdapter($orderAdapter);
            $this->getRequestAntiFraud()->setPaymentData($paymentData);
            
            $this->getRequest()->setAntiFraudRequest($this->getRequestAntiFraud());
        }

        $this->getRequest()->setOrderAdapter($orderAdapter);
        $this->getRequest()->setPaymentData($paymentData);

        return $this->getRequest();
    }

    /**
     * @return bool
     */
    protected function hasAntiFraud()
    {
        if (! $this->getScopeConfig()->getValue(
            AntiFraudConfigInterface::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE
        )) {
            return false;
        }

        return true;
    }

    /**
     * @param BaseRequestInterface $request
     * @return $this
     */
    protected function setRequest(BaseRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return RequestInterface
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RequestAntiFraudLibInterface $requestAntiFraud
     * @return $this
     */
    public function setAntiFraudRequest(RequestAntiFraudLibInterface $requestAntiFraud)
    {
        $this->requestAntiFraud = $requestAntiFraud;
        return $this;
    }

    /**
     * @return RequestAntiFraudLibInterface|RequestAntiFraudLibInterface|RequestInterface
     */
    public function getRequestAntiFraud()
    {
        return $this->requestAntiFraud;
    }

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @return $this
     */
    protected function setScopeConfig(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        return $this;
    }

    /**
     * @return ScopeConfigInterface
     */
    protected  function getScopeConfig()
    {
        return $this->scopeConfig;
    }
}
