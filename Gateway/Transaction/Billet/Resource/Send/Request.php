<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;

/**
 * Braspag Transaction Billet Send Request
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
	protected $quote;
	protected $config;
	protected $billingAddress;

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
        return $this->getQuote()->getData($this->getConfig()->getIdentityAttributeCode());
    }

    public function getCustomerIdentityType()
    {
        $identity = (int) preg_replace('/[^0-9]/','', $this->getCustomerIdentity());
        return (strlen($identity) === 14) ? 'CNPJ' : 'CPF';
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
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerStreetAttribute());
    }

    public function getCustomerAddressNumber()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerNumberAttribute());

    }

    public function getCustomerAddressComplement()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerComplementAttribute());
    }

    public function getCustomerAddressZipCode()
    {
        return preg_replace('/[^0-9]/','', $this->getBillingAddress()->getPostcode());
    }

    public function getCustomerAddressDistrict()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerDistrictAttribute());
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

	public function getPaymentAmount()
	{
		$amount = (float) $this->getOrderAdapter()->getGrandTotalAmount() * 100;
		return str_replace('.', '', $amount);
	}

	public function getPaymentAddress()
	{
		$address = $this->getBillingAddress();

		return sprintf("%s %s %s/%s - %s", $this->getCustomerAddressStreet(), $this->getCustomerAddressNumber(), $address->getCity(), $address->getRegionCode(), $address->getPostcode());
	}

	public function getPaymentProvider()
	{
		return $this->getConfig()->getPaymentProvider();
	}

	public function getPaymentBoletoNumber()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

	public function getPaymentAssignor()
	{
		return $this->getConfig()->getPaymentAssignor();
	}

	public function getPaymentDemonstrative()
	{
		return $this->getConfig()->getPaymentDemonstrative();
	}

	public function getPaymentExpirationDate()
	{
		return $this->getConfig()->getExpirationDate();
	}

	public function getPaymentIdentification()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

	public function getPaymentInstructions()
	{
		return $this->getConfig()->getPaymentInstructions();
	}

    protected function getOrderAdapter()
    {
        return $this->order;
    }

    public function setOrderAdapter(OrderAdapterInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->getConfig()->getSession()->getQuote();
        }

        return $this->quote;
    }

    protected function getBillingAddressAttribute($attribute)
    {
        if (preg_match('/^street_/', $attribute)) {
            $line = (int) str_replace('street_', '', $attribute);
            return $this->getQuoteBillingAddress()->getStreetLine($line);
        }

        $this->getQuoteBillingAddress()->getData($attribute);
    }

    protected function getQuoteBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }
}
