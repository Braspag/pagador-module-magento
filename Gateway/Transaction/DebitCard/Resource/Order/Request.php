<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Model\InfoInterface;

/**
 * Debit Order request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Request implements BraspagMagentoRequestInterface, BraspaglibRequestInterface
{
    protected $config;

    protected $orderAdapter;

    protected $paymentData;

    protected $billingAddress;

    public function __construct(
        ConfigInterface $config
    ){
        $this->setConfig($config);
    }

    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;

        return $this;
    }

    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
    }

    public function getMerchantId()
    {
        return $this->getConfig()->getMerchantId();
    }

    public function getMerchantKey()
    {
        return $this->getConfig()->getMerchantKey();
    }

    public function getMerchantOrderId()
    {
        return $this->getOrderAdapter()->getOrderIncrementId();
    }

    public function isTestEnvironment()
    {
        return $this->getConfig()->getIsTestEnvironment();
    }

    public function getCustomerName()
    {
    	return $this->getBillingAddress()->getFirstname() . ' ' . $this->getBillingAddress()->getLastname();
    }

    public function getPaymentAmount()
    {
        $amount = $this->getOrderAdapter()->getGrandTotalAmount() * 100;

        return str_replace('.', '', $amount);
    }

    public function getPaymentProvider()
    {
        List($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);
        
        return $provider;
    }

    public function getPaymentReturnUrl()
    {
    	return $this->getConfig()->getPaymentReturnUrl();
    }

    public function getPaymentDebitCardCardNumber()
    {
    	return $this->getPaymentData()->getCcNumber();
    }

    public function getPaymentDebitCardHolder()
    {
    	return $this->getPaymentData()->getCcOwner();
    }

    public function getPaymentDebitCardExpirationDate()
    {
    	return str_pad($this->getPaymentData()->getCcExpMonth(), 2, '0', STR_PAD_LEFT) . '/' . $this->getPaymentData()->getCcExpYear();
    }

    public function getPaymentDebitCardSecurityCode()
    {
    	return $this->getPaymentData()->getCcCid();
    }

    public function getPaymentDebitCardBrand()
    {
        List($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);
        
        return $brand;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
    }

    protected function getPaymentData()
    {
        return $this->paymentData;
    }

    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }
}