<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\BraspagPagador\Helper\Validator;

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
    /**
     * @var
     */
    protected $orderAdapter;
    /**
     * @var
     */
    protected $quote;
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $billingAddress;
    /**
     * Helper validator.
     *
     * @var Validator
     */
    protected $validator;

    protected $helperData;

    /**
     * Request constructor.
     *
     * @param ConfigInterface $config
     * @param Validator $validator
     */
    public function __construct(
		ConfigInterface $config,
        Validator $validator,
        \Webjump\BraspagPagador\Helper\Data $helperData

    ) {
		$this->setConfig($config);
        $this->validator = $validator;
        $this->helperData = $helperData;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
	{
		return $this->getConfig()->getMerchantId();
	}

    /**
     * @return mixed
     */
    public function getMerchantKey()
	{
		return $this->getConfig()->getMerchantKey();
	}

    /**
     * @return mixed
     */
    public function isTestEnvironment()
    {
        return $this->getConfig()->getIsTestEnvironment();
    }

    /**
     * @return mixed
     */
    public function getMerchantOrderId()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

    /**
     * @return string
     */
    public function getCustomerName()
	{
	    $customerName = $this->getOrderAdapter()->getBillingAddress()->getFirstname(). ' ' .
            $this->getOrderAdapter()->getBillingAddress()->getLastname();

        return $this->helperData->removeSpecialCharacters($customerName);
	}

    /**
     * @return mixed
     */
    public function getCustomerIdentity()
    {
        $attribute = $this->getConfig()->getIdentityAttributeCode();

        return $this->helperData->removeSpecialCharactersFromTaxvat(
            $this->getQuote()->getBillingAddress()->getData($attribute)
        ) ?: $this->helperData->removeSpecialCharactersFromTaxvat(
            $this->getQuote()->getData($attribute)
        );
    }

    /**
     * @return string
     */
    public function getCustomerIdentityType()
    {
        $identity = (string) preg_replace('/[^0-9]/','', $this->getCustomerIdentity());
        return (strlen($identity) > 11) ? 'CNPJ' : 'CPF';
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->getBillingAddress()->getEmail();
    }

    /**
     * @return null
     */
    public function getCustomerBirthDate()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getCustomerAddressStreet()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerStreetAttribute());
    }

    /**
     * @return string
     */
    public function getCustomerAddressNumber()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerNumberAttribute());

    }

    /**
     * @return string
     */
    public function getCustomerAddressComplement()
    {
        return $this->getBillingAddressAttribute($this->getConfig()->getCustomerComplementAttribute());
    }

    /**
     * @return mixed
     */
    public function getCustomerAddressZipCode()
    {
        return preg_replace('/[^0-9]/','', $this->getBillingAddress()->getPostcode());
    }

    /**
     * @return string
     */
    public function getCustomerAddressDistrict()
    {
        return $this->validator->sanitizeDistrict($this->getBillingAddressAttribute($this->getConfig()->getCustomerDistrictAttribute()));
    }

    /**
     * @return mixed
     */
    public function getCustomerAddressCity()
    {
        return $this->getBillingAddress()->getCity();
    }

    /**
     * @return mixed
     */
    public function getCustomerAddressState()
    {
        return $this->getBillingAddress()->getRegionCode();
    }

    /**
     * @return string
     */
    public function getCustomerAddressCountry()
    {
        return 'BRA';
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
	{
		$amount = (float) round($this->getOrderAdapter()->getGrandTotalAmount(), 2) * 100;
		return str_replace('.', '', $amount);
	}

    /**
     * @return string
     */
    public function getPaymentAddress()
	{
        return $this->getConfig()->getPaymentAssignorAddress();
	}

    /**
     * @return mixed
     */
    public function getPaymentProvider()
	{
		return $this->getConfig()->getPaymentProvider();
	}

    /**
     * @return mixed
     */
    public function getPaymentBoletoNumber()
	{
		return $this->getOrderAdapter()->getOrderIncrementId();
	}

    /**
     * @return mixed
     */
    public function getPaymentAssignor()
	{
		return $this->getConfig()->getPaymentAssignor();
	}

    /**
     * @return mixed
     */
    public function getPaymentDemonstrative()
	{
		return $this->getConfig()->getPaymentDemonstrative();
	}

    /**
     * @return mixed
     */
    public function getPaymentExpirationDate()
	{
		return $this->getConfig()->getExpirationDate();
	}

    /**
     * @return mixed
     */
    public function getPaymentIdentification()
	{
		return $this->getConfig()->getPaymentIdentification();
	}

    /**
     * @return mixed
     */
    public function getPaymentInstructions()
	{
		return $this->getConfig()->getPaymentInstructions();
	}

    /**
     * @return mixed
     */
    protected function getOrderAdapter()
    {
        return $this->order;
    }

    /**
     * @param OrderAdapterInterface $order
     * @return $this
     */
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
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->getConfig()->getSession()->getQuote();
        }

        return $this->quote;
    }

    /**
     * @todo
     * @return \Magento\Customer\Api\Data\CustomerInterface|\Magento\Framework\Api\ExtensibleDataInterface
     */
    public function getCustomerBillet()
    {
        return $this->getQuote()->getCustomer();
    }

    /**
     * @param $attribute
     * @return string
     */
    protected function getBillingAddressAttribute($attribute)
    {
        if (preg_match('/^street_/', $attribute)) {
            $line = (int) str_replace('street_', '', $attribute);
            return $this->getQuoteBillingAddress()->getStreetLine($line);
        }

        $this->getQuoteBillingAddress()->getData($attribute);
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address
     */
    protected function getQuoteBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    /**
     * @return mixed
     */
    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }
}
