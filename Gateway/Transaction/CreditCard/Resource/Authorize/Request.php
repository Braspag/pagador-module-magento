<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\RequestInterface as BraspagMagentoRequestInterface;
use Magento\Payment\Model\InfoInterface;

/**
 * Braspag Transaction CreditCard Authorize Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Request implements BraspagMagentoRequestInterface, BraspaglibRequestInterface
{
	protected $orderAdapter;

    protected $paymentData;

	protected $config;

	public function __construct(
		ConfigInterface $config
	) {
		$this->setConfig($config);
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

    public function getCustomerName()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getFirstname() . ' ' . $this->getOrderAdapter()->getBillingAddress()->getLastname();
    }

    public function getCustomerIdentity()
    {
        return false;
    }

    public function getCustomerIdentityType()
    {
        return false;
    }

    public function getCustomerEmail()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getEmail();
    }

    public function getCustomerBirthDate()
    {
        return false;
    }

    public function getCustomerAddressStreet()
    {
        List($street,  $streetNumber) = explode(', ', $this->getOrderAdapter()->getBillingAddress()->getStreetLine1());

        return $street;
    }

    public function getCustomerAddressNumber()
    {
        List($street,  $streetNumber) = explode(', ', $this->getOrderAdapter()->getBillingAddress()->getStreetLine1());

        return $streetNumber;
    }

    public function getCustomerAddressComplement()
    {
        return false;
    }

    public function getCustomerAddressZipCode()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getPostcode();
    }

    public function getCustomerAddressDistrict()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getStreetLine2();
    }

    public function getCustomerAddressCity()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getCity();
    }

    public function getCustomerAddressState()
    {
        return $this->getOrderAdapter()->getBillingAddress()->getRegionCode();
    }

    public function getCustomerAddressCountry()
    {
        return 'BR';
    }

    public function getCustomerDeliveryAddressStreet()
    {
        List($street,  $streetNumber) = explode(',', $this->getOrderAdapter()->getShippingAddress()->getStreetLine1());

        return trim($street);
    }

    public function getCustomerDeliveryAddressNumber()
    {
        List($street,  $streetNumber) = explode(',', $this->getOrderAdapter()->getShippingAddress()->getStreetLine1());

        return trim($streetNumber);
    }

    public function getCustomerDeliveryAddressComplement()
    {
        return false;
    }

    public function getCustomerDeliveryAddressZipCode()
    {
        return $this->getOrderAdapter()->getShippingAddress()->getPostcode();
    }

    public function getCustomerDeliveryAddressDistrict()
    {
        return $this->getOrderAdapter()->getShippingAddress()->getStreetLine2();
    }

    public function getCustomerDeliveryAddressCity()
    {
        return $this->getOrderAdapter()->getShippingAddress()->getCity();
    }

    public function getCustomerDeliveryAddressState()
    {
        return $this->getOrderAdapter()->getShippingAddress()->getRegionCode();
    }

    public function getCustomerDeliveryAddressCountry()
    {
        return 'BR';
    }

    public function getPaymentAmount()
    {
        $amount = $this->getOrderAdapter()->getGrandTotalAmount() * 100;

        return str_replace('.', '', $amount);
    }

    public function getPaymentCurrency()
    {
        return 'BRL';
    }

    public function getPaymentCountry()
    {
        return 'BRA';
    }

    public function getPaymentProvider()
    {
        return $this->getPaymentData()->getCcType();
    }

    public function getPaymentServiceTaxAmount()
    {
        
    }

    public function getPaymentInstallments()
    {

    }

    public function getPaymentInterest()
    {

    }

    public function getPaymentCapture()
    {

    }

    public function getPaymentAuthenticate()
    {

    }

    public function getPaymentSoftDescriptor()
    {

    }

    public function getPaymentCreditCardCardNumber()
    {

    }

    public function getPaymentCreditCardHolder()
    {

    }

    public function getPaymentCreditCardExpirationDate()
    {

    }

    public function getPaymentCreditCardSecurityCode()
    {

    }

    public function getPaymentCreditCardSaveCard()
    {

    }

    public function getPaymentCreditCardBrand()
    {

    }

    public function getPaymentExtraDataCollection()
    {

    }

    public function getAntiFraudRequest()
    {

    }

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
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

    protected function getPaymentData()
    {
        return $this->paymentData;
    }
}
