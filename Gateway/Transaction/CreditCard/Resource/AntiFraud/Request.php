<?php
/**
 * Capture Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud;

use Magento\Payment\Gateway\Data\Order\OrderAdapter;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface as ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Magento\Payment\Model\InfoInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Items\RequestFactory;

class Request implements BraspaglibRequestInterface, BraspagMagentoRequestInterface
{
    protected $config;
    protected $orderAdapter;
    protected $paymentData;
    protected $requestItemFactory;
    protected $billingAddress;
    protected $shippingAddress;
    protected $quote;
    protected $fingerPrintId;

    /**
     * @param ConfigInterface $config
     * @param RequestFactory $requestItemFactory
     * @todo inject config anti-fraud
     */
    public function __construct(ConfigInterface $config, RequestFactory $requestItemFactory)
    {
        $this->setConfig($config);
        $this->setRequestItemFactory($requestItemFactory);
    }

    public function getSequence()
    {
        return $this->getConfig()->getSequence();
    }

    public function getSequenceCriteria()
    {
        return $this->getConfig()->getSequenceCriteria();
    }

    public function getFingerPrintId()
    {
        
        return $this->getConfig()->getSession()->getSessionId();
    }

    public function getCaptureOnLowRisk()
    {
        return (boolean) $this->getConfig()->getCaptureOnLowRisk();
    }

    public function getVoidOnHighRisk()
    {
        return (boolean) $this->getConfig()->getVoidOnHighRisk();
    }

    /**
     * @todo to implementation
     */
    public function getBrowserCookiesAccepted()
    {
    }

    /**
     * @todo to implementation
     */
    public function getBrowserEmail()
    {
    }

    /**
     * @todo to implementation
     */
    public function getBrowserHostName()
    {
    }

    public function getBrowserIpAddress()
    {
        return $this->getOrderAdapter()->getRemoteIp();
    }

    /**
     * @todo to implementation
     */
    public function getBrowserType()
    {
    }

    /**
     * @todo to implementation
     */
    public function getCartIsGift()
    {
    }

    /**
     * @todo to implementation
     */
    public function getCartReturnsAccepted()
    {
    }

    public function getCartItems()
    {
        $items = [];
        foreach ($this->getOrderAdapter()->getItems() as $item) {
            $items[] = $this->getRequestItemFactory()->create($item);
        }

        return $items;
    }

    public function getCartShippingAddressee()
    {
        return  $this->getShippingAddress()->getFirstname() . ' ' .
                ($this->getShippingAddress()->getMiddlename()) ? $this->getShippingAddress()->getMiddlename() . ' ' : '' .
                $this->getShippingAddress()->getLastname();
    }

    /**
     * @todo to implementation
     */
    public function getCartShippingMethod()
    {
    }

    public function getCartShippingPhone()
    {
        return ConfigInterface::COUNTRY_TELEPHONE_CODE .
               preg_replace('/[^0-9]/', '', $this->getOrderAdapter()->getBillingAddress()->getTelephone());
    }

    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;
        return $this;
    }

    /**
     * @param InfoInterface $payment
     */
    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
    }

    /**
     * @return InfoInterface
     */
    public function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * @return OrderAdapter
     */
    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return RequestFactory
     */
    public function getRequestItemFactory()
    {
        return $this->requestItemFactory;
    }

    /**
     * @param RequestFactory $requestItemFactory
     */
    public function setRequestItemFactory(RequestFactory $requestItemFactory)
    {
        $this->requestItemFactory = $requestItemFactory;
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
     * @return \Magento\Payment\Gateway\Data\AddressAdapterInterface|null
     */
    protected function getShippingAddress()
    {
        if (!$this->shippingAddress) {
            $this->shippingAddress = $this->getOrderAdapter()->getShippingAddress();
        }

        return $this->shippingAddress;
    }

    /**
     * @return \Magento\Payment\Gateway\Data\AddressAdapterInterface|null
     */
    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (! $this->quote) {
            $this->quote =  $this->getConfig()->getSession()->getQuote();
        }

        return $this->quote;
    }
}
