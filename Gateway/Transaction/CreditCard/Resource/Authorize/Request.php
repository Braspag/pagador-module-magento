<?php
/**
 * Braspag Transaction CreditCard Authorize Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;

use Magento\Payment\Model\InfoInterface;

class Request implements BraspaglibRequestInterface, RequestInterface
{
    protected $orderAdapter;
    protected $paymentData;
    protected $config;
    protected $installmentsConfig;
    protected $billingAddress;
    protected $shippingAddress;
    protected $antiFraudRequest;
    protected $quote;
    protected $avsRequest;

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
        return  $this->getOrderAdapter()->getOrderIncrementId();
    }

    public function getCustomerName()
    {
        return trim($this->getBillingAddress()->getFirstname() . ' ' . $this->getBillingAddress()->getLastname());
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

    public function getCustomerDeliveryAddressStreet()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerStreetAttribute());
    }

    public function getCustomerDeliveryAddressNumber()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerNumberAttribute());
    }

    public function getCustomerDeliveryAddressComplement()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerComplementAttribute());
    }

    public function getCustomerDeliveryAddressZipCode()
    {
        return $this->getShippingAddress()->getPostcode();
    }

    public function getCustomerDeliveryAddressDistrict()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerDistrictAttribute());
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

    public function getPaymentType()
    {
        return false;
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
        list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

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
        return (bool) $this->getConfig()->isAuthorizeAndCapture();
    }

    public function getPaymentAuthenticate()
    {
        return (bool) $this->getConfig()->isAuthenticate3DsVbv();
    }

    public function getReturnUrl()
    {
        return $this->getConfig()->getReturnUrl();
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
        list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

        return ($brand) ? $brand : 'Visa';
    }

    public function getPaymentExtraDataCollection()
    {
        return null;
    }

    /**
     * @return RequestAntiFraudLibInterface
     */
    public function getAntiFraudRequest()
    {
        return $this->antiFraudRequest;
    }

    /**
     * @param RequestAntiFraudLibInterface $antiFraudRequest
     */
    public function setAntiFraudRequest(RequestAntiFraudLibInterface $antiFraudRequest)
    {
        $this->antiFraudRequest = $antiFraudRequest;
    }

    /**
     * @param RequestAvsLibInterface $avsRequest
     * @return $this
     */
    public function setAvsRequest(RequestAvsLibInterface $avsRequest)
    {
        $this->avsRequest = $avsRequest;

        return $this;
    }

    public function getAvsRequest()
    {
        return $this->avsRequest;
    }

    /**
     * @param OrderAdapterInterface $orderAdapter
     * @return $this
     */
    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;

        return $this;
    }

    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
    }

    public function getPaymentCreditCardCardToken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_token');
    }

    public function getPaymentCreditSoptpaymenttoken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_soptpaymenttoken');
    }

    protected function getBillingAddressAttribute($attribute)
    {
        if (preg_match('/^street_/', $attribute)) {
            $line = (int) str_replace('street_', '', $attribute);
            return $this->getQuoteBillingAddress()->getStreetLine($line);
        }

        $this->getQuoteBillingAddress()->getData($attribute);
    }

    protected function getShippingAddressAttribute($attribute)
    {
        if (preg_match('/^street_/', $attribute)) {
            $line = (int) str_replace('street_', '', $attribute);
            return $this->getQuoteShippingAddress()->getStreetLine($line);
        }

        $this->getQuoteShippingAddress()->getData($attribute);
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

    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
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

    protected function getQuoteBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    protected function getQuoteShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }
}
