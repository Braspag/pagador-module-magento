<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
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

    protected $installmentsConfig;

    protected $billingAddress;

    protected $shippingAddress;

	public function __construct(
		ConfigInterface $config,
        InstallmentsConfigInterface $installmentsConfig
	) {
		$this->setConfig($config);
        $this->setInstallmentsConfig($installmentsConfig);
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
        return $this->getBillingAddress()->getFirstname() . ' ' . $this->getBillingAddress()->getLastname();
    }

    public function getCustomerIdentity()
    {
        return null;
    }

    public function getCustomerIdentityType()
    {
        return null;
    }

    public function getCustomerEmail()
    {
        return $this->getBillingAddress()->getEmail();
    }

    public function getCustomerBirthDate()
    {
        return null;
    }

    public function getCustomerAddressStreet()
    {
        List($street,  $streetNumber) = array_pad(explode(',', $this->getBillingAddress()->getStreetLine1(), 2), 2, null);

        return trim($street);
    }

    public function getCustomerAddressNumber()
    {
        List($street,  $streetNumber) = array_pad(explode(',', $this->getBillingAddress()->getStreetLine1(), 2), 2, null);

        return (int) $streetNumber;
    }

    public function getCustomerAddressComplement()
    {
        return null;
    }

    public function getCustomerAddressZipCode()
    {
        return $this->getBillingAddress()->getPostcode();
    }

    public function getCustomerAddressDistrict()
    {
        return $this->getBillingAddress()->getStreetLine2();
    }

    public function getCustomerAddressCity()
    {
        return $this->getBillingAddress()->getCity();
    }

    public function getCustomerAddressState()
    {
        return $this->getBillingAddress()->getRegionCode();
    }

    public function getCustomerAddressCountry()
    {
        return 'BRA';
    }

    public function getCustomerDeliveryAddressStreet()
    {
        List($street,  $streetNumber) = array_pad(explode(',', $this->getShippingAddress()->getStreetLine1(), 2), 2, null);

        return trim($street);
    }

    public function getCustomerDeliveryAddressNumber()
    {
        List($street,  $streetNumber) = array_pad(explode(',', $this->getShippingAddress()->getStreetLine1(), 2), 2, null);

        return (int) $streetNumber;
    }

    public function getCustomerDeliveryAddressComplement()
    {
        return null;
    }

    public function getCustomerDeliveryAddressZipCode()
    {
        return $this->getShippingAddress()->getPostcode();
    }

    public function getCustomerDeliveryAddressDistrict()
    {
        return $this->getShippingAddress()->getStreetLine2();
    }

    public function getCustomerDeliveryAddressCity()
    {
        return $this->getShippingAddress()->getCity();
    }

    public function getCustomerDeliveryAddressState()
    {
        return $this->getShippingAddress()->getRegionCode();
    }

    public function getCustomerDeliveryAddressCountry()
    {
        return 'BRA';
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
        List($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);
        
        return $provider;
    }

    public function getPaymentServiceTaxAmount()
    {
        return 0;
    }

    public function getPaymentInstallments()
    {
        if (!$installments = $this->getPaymentData()->getAdditionalInformation('cc_installments')) {
            $installments = 1;
        }

        return $installments;
    }

    public function getPaymentInterest()
    {
        return $this->getInstallmentsConfig()->isInterestByIssuer() ? 'ByIssuer' : 'ByMerchant';
    }

    public function getPaymentCapture()
    {
        return $this->getConfig()->isAuthorizeAndCapture();
    }

    public function getPaymentAuthenticate()
    {
        return null;
    }

    public function getPaymentSoftDescriptor()
    {
        return $this->getConfig()->getSoftDescriptor();
    }

    public function getPaymentCreditCardCardNumber()
    {
        return $this->getPaymentData()->getCcNumber();
    }

    public function getPaymentCreditCardHolder()
    {
        return $this->getPaymentData()->getCcOwner();
    }

    public function getPaymentCreditCardExpirationDate()
    {
        return str_pad($this->getPaymentData()->getCcExpMonth(), 2, '0', STR_PAD_LEFT) . '/' . $this->getPaymentData()->getCcExpYear();
    }

    public function getPaymentCreditCardSecurityCode()
    {
        return $this->getPaymentData()->getCcCid();
    }

    public function getPaymentCreditCardSaveCard()
    {
        return (boolean) $this->getPaymentData()->getAdditionalInformation('cc_savecard');
    }

    public function getPaymentCreditCardBrand()
    {
        List($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);
        
        return $brand;
    }

    public function getPaymentExtraDataCollection()
    {
        return null;
    }

    public function getAntiFraudRequest()
    {
        return null;
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

    protected function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }

    protected function getShippingAddress()
    {
        if (!$this->shippingAddress) {
            $this->shippingAddress = $this->getOrderAdapter()->getShippingAddress();
        }

        return $this->shippingAddress;
    }

    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }

    public function getPaymentCreditCardCardToken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_token');
    }

    public function getPaymentCreditSoptpaymenttoken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_soptpaymenttoken');
    }
}
