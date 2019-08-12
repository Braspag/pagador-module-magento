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
use Webjump\BraspagPagador\Helper\Validator;
use Magento\Payment\Model\InfoInterface;
use Webjump\BraspagPagador\Helper\GrandTotal\Pricing as GrandTotalPricingHelper;

/**
 * Class Request
 * @package Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize
 */
class Request implements BraspaglibRequestInterface, RequestInterface
{
    /**
     * @var
     */
    protected $orderAdapter;
    /**
     * @var
     */
    protected $paymentData;
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $installmentsConfig;
    /**
     * @var
     */
    protected $billingAddress;
    /**
     * @var
     */
    protected $shippingAddress;
    /**
     * @var
     */
    protected $antiFraudRequest;
    /**
     * @var
     */
    protected $quote;
    /**
     * @var
     */
    protected $avsRequest;
    /**
     * Helper validator.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * @var GrandTotalPricingHelper
     */
    protected $grandTotalPricingHelper;

    protected $helperData;

    /**
     * Request constructor.
     * @param ConfigInterface $config
     * @param InstallmentsConfigInterface $installmentsConfig
     * @param Validator $validator
     */
    public function __construct(
        ConfigInterface $config,
        InstallmentsConfigInterface $installmentsConfig,
        Validator $validator,
        GrandTotalPricingHelper $grandTotalPricingHelper,
        \Webjump\BraspagPagador\Helper\Data $helperData
    ) {
        $this->setConfig($config);
        $this->setInstallmentsConfig($installmentsConfig);
        $this->validator = $validator;
        $this->grandTotalPricingHelper = $grandTotalPricingHelper;
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
        return  $this->getOrderAdapter()->getOrderIncrementId();
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->helperData->removeSpecialCharacters(
            trim($this->getBillingAddress()->getFirstname() . ' ' . $this->getBillingAddress()->getLastname())
        );
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
     * @return array
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
    public function getCustomerAddressPhone()
    {
        return $this->getBillingAddress()->getTelephone();
    }

    /**
     * @return string
     */
    public function getCustomerDeliveryAddressStreet()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerStreetAttribute());
    }

    /**
     * @return string
     */
    public function getCustomerDeliveryAddressNumber()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerNumberAttribute());
    }

    /**
     * @return string
     */
    public function getCustomerDeliveryAddressComplement()
    {
        return $this->getShippingAddressAttribute($this->getConfig()->getCustomerComplementAttribute());
    }

    /**
     * @return mixed
     */
    public function getCustomerDeliveryAddressZipCode()
    {
        if ($this->getShippingAddress()) {
            return $this->getShippingAddress()->getPostcode();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCustomerDeliveryAddressDistrict()
    {
        return $this->validator->sanitizeDistrict($this->getShippingAddressAttribute($this->getConfig()->getCustomerDistrictAttribute()));
    }

    /**
     * @return mixed
     */
    public function getCustomerDeliveryAddressCity()
    {
        if ($this->getShippingAddress()) {
            return $this->getShippingAddress()->getCity();
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getCustomerDeliveryAddressState()
    {
        if ($this->getShippingAddress()) {
            return $this->getShippingAddress()->getRegionCode();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCustomerDeliveryAddressCountry()
    {
        if (!$this->getShippingAddress()) {
            return '';
        }

        return 'BRA';
    }

    /**
     * @return mixed
     */
    public function getCustomerDeliveryAddressPhone()
    {
        return $this->getShippingAddress()->getTelephone();
    }

    /**
     * @return bool
     */
    public function getPaymentType()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        $grandTotalAmount = $this->getOrderAdapter()->getGrandTotalAmount();
        $integerValue = $this->grandTotalPricingHelper->currency($grandTotalAmount);

        return $integerValue;
    }

    /**
     * @return string
     */
    public function getPaymentCurrency()
    {
        return 'BRL';

    }

    /**
     * @return string
     */
    public function getPaymentCountry()
    {
        return 'BRA';
    }

    /**
     * @return mixed
     */
    public function getPaymentProvider()
    {
        list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

        return $provider;
    }

    /**
     * @return int
     */
    public function getPaymentServiceTaxAmount()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getPaymentInstallments()
    {
        if (!$installments = $this->getPaymentData()->getAdditionalInformation('cc_installments')) {
            $installments = 1;
        }

        return $installments;
    }

    /**
     * @return string
     */
    public function getPaymentInterest()
    {
        return $this->getInstallmentsConfig()->isInterestByIssuer() ? 'ByIssuer' : 'ByMerchant';
    }

    /**
     * @return bool
     */
    public function getPaymentCapture()
    {
        return (bool) $this->getConfig()->isAuthorizeAndCapture();
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->getConfig()->getReturnUrl();
    }

    /**
     * @return mixed
     */
    public function getPaymentSoftDescriptor()
    {
        return $this->getConfig()->getSoftDescriptor();
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardCardNumber()
    {
        return $this->getPaymentData()->getCcNumber();
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardHolder()
    {
        return $this->getPaymentData()->getCcOwner();
    }

    /**
     * @return string
     */
    public function getPaymentCreditCardExpirationDate()
    {
        return str_pad($this->getPaymentData()->getCcExpMonth(), 2, '0', STR_PAD_LEFT) . '/' . $this->getPaymentData()->getCcExpYear();
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardSecurityCode()
    {
        return $this->getPaymentData()->getCcCid();
    }

    /**
     * @return bool
     */
    public function getPaymentCreditCardSaveCard()
    {
        return (boolean) $this->getPaymentData()->getAdditionalInformation('cc_savecard');
    }

    /**
     * @return string
     */
    public function getPaymentCreditCardBrand()
    {
        list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

        return ($brand) ? $brand : 'Visa';
    }

    /**
     * @return bool
     */
    public function getPaymentAuthenticate()
    {
        return (bool) $this->getConfig()->isAuth3Ds20Active()
            && $this->getPaymentExternalAuthenticationFailureType() != ConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED;
    }

    /**
     * @return string
     */
    public function getPaymentExternalAuthenticationFailureType()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_failure_type');
    }

    /**
     * @return mixed
     */
    public function getPaymentExternalAuthenticationCavv()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_cavv');
    }

    /**
     * @return string
     */
    public function getPaymentExternalAuthenticationXid()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_xid');
    }

    /**
     * @return string
     */
    public function getPaymentExternalAuthenticationEci()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_eci');
    }

    /**
     * @return string
     */
    public function getPaymentCardExternalAuthenticationVersion()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_version');
    }

    /**
     * @return string
     */
    public function getPaymentExternalAuthenticationReferenceId()
    {
        return $this->getPaymentData()->getAdditionalInformation('authentication_reference-id');
    }

    /**
     * @return null
     */
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

    /**
     * @return mixed
     */
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

    /**
     * @param InfoInterface $payment
     */
    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardCardToken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_token');
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditSoptpaymenttoken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_soptpaymenttoken');
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
     * @param $attribute
     * @return string
     */
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
    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
    }

    /**
     * @return mixed
     */
    protected function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * @return mixed
     */
    protected function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    /**
     * @param InstallmentsConfigInterface $installmentsConfig
     * @return $this
     */
    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getShippingAddress()
    {
        if (!$this->shippingAddress) {
            $this->shippingAddress = $this->getOrderAdapter()->getShippingAddress();
        }

        return $this->shippingAddress;
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

    /**
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
        return $this;
    }

    /**
     * @todo
     * @return ConfigInterface
     */
    public function getCustomerCreditCard()
    {
        return $this->getQuote()->getCustomer();
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address
     */
    protected function getQuoteBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address
     */
    protected function getQuoteShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }
}
