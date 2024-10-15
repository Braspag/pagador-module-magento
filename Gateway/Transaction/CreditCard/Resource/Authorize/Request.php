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

namespace Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Braspag\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as BraspaglibRequestInterface;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Braspag\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;
use Braspag\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Braspag\BraspagPagador\Helper\Validator;
use Magento\Payment\Model\InfoInterface;
use Braspag\BraspagPagador\Helper\GrandTotal\Pricing as GrandTotalPricingHelper;
use Braspag\BraspagPagador\Model\Request\CardTwo as TowCard;


/**
 * Class Request
 * @package Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize
 */
class Request implements BraspaglibRequestInterface, RequestInterface
{
    const CODE_BY_CARD = '2';

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
     * @var
     */
    protected $paymentSplitRequest;

    /**
     * Helper validator.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * @var
     */
    protected $typeCard;


    /**
     * @var GrandTotalPricingHelper
     */
    protected $grandTotalPricingHelper;

    protected $helperData;

    protected $cardTwo;


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
        \Braspag\BraspagPagador\Helper\Data $helperData,
        TowCard $cardTwo
    ) {
        $this->setConfig($config);
        $this->setInstallmentsConfig($installmentsConfig);
        $this->validator = $validator;
        $this->grandTotalPricingHelper = $grandTotalPricingHelper;
        $this->helperData = $helperData;
        $this->cardTwo = $cardTwo;
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
        //return $this->getCardType() == 'two_card' ?  $this->getOrderAdapter()->getOrderIncrementId() . '_' . self::CODE_BY_CARD :  $this->getOrderAdapter()->getOrderIncrementId();

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

        if ($this->getCardType() == 'two_card') {
            $taxVat = $this->cardTwo->getData('taxvat_card2');
            if (!is_null($taxVat) || isset($taxVat))
                return $this->helperData->removeSpecialCharactersFromTaxvat($taxVat);
        }

        if ($this->getCardType() == 'primary_card') {
            $taxVat = $this->cardTwo->getData('taxvat_card');
            if (!is_null($taxVat) || isset($taxVat))
                return $this->helperData->removeSpecialCharactersFromTaxvat($taxVat);
        }

        $ccTaxvat = $this->getPaymentData()->getAdditionalInformation('cc_taxvat');
        if (!is_null($ccTaxvat) || isset($ccTaxvat))
            return $this->helperData->removeSpecialCharactersFromTaxvat($ccTaxvat);


        return $this->helperData->removeSpecialCharactersFromTaxvat(
            ($this->getQuote()->getBillingAddress()->getData('vat_id') != null) ?
                $this->getQuote()->getBillingAddress()->getData('vat_id') :
                $this->getQuote()->getData('customer_taxvat')
        );
    }

    /**
     * @return string
     */
    public function getCustomerIdentityType()
    {
        $customerIdenty = $this->getCustomerIdentity();

        if ($customerIdenty) {
            return (strlen((string) preg_replace('/[^0-9]/', '', $customerIdenty)) > 11) ? 'CNPJ' : 'CPF';
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->getBillingAddress()->getEmail();
    }

    /**
     * @param  $typeCard
     * @return $this
     */
    public function setCardType($typeCard)
    {
        $this->typeCard = $typeCard;
    }


    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->typeCard;
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
        return preg_replace('/[^0-9]/', '', $this->getBillingAddress()->getPostcode());
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
     * @return mixed
     */
    public function getFromDevice()
    {
        return  $this->getPaymentData()->getAdditionalInformation('from_device');
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
        $phone =  preg_replace('/[^0-9]/', '', $this->getBillingAddress()->getTelephone());
        return '+'.ConfigInterface::COUNTRY_TELEPHONE_CODE . ' '.
              substr($phone, 0, 2) .' '. substr($phone, 2,5) .'-'. substr($phone, 7);

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

        try {
            $grandTotalAmount = $this->getOrderAdapter()->getGrandTotalAmount();
            $installment = $this->getPaymentInstallments();

            if ($this->getCardType() == 'two_card')
                $grandTotalAmount =   str_replace(',', '.',  str_replace('.', '',  $this->cardTwo->getData('total_amount')));

            if ($this->getCardType() == 'primary_card')
                $grandTotalAmount =  $grandTotalAmount - str_replace(',', '.',  str_replace('.', '',  $this->cardTwo->getData('total_amount')));


            if ($this->getInstallmentsConfig()->isInterestByIssuer() && ($installment > $this->getInstallmentsConfig()->getinstallmentsMaxWithoutInterest())) {
                $amountTotal =  $this->calcPriceWithInterest($installment, $grandTotalAmount, $this->getInstallmentsConfig()->getInterestRate());
                $integerValue = $this->grandTotalPricingHelper->currency($amountTotal * $installment);
            } else {
                $integerValue = $this->grandTotalPricingHelper->currency($grandTotalAmount);
            }

        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Error getPaymentAmount');
        }

        if ($integerValue <= 0 || is_null($integerValue))
            throw new \InvalidArgumentException('Amount value zero');

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

        $ccType = $this->getPaymentData()->getCcType();

        // if ($this->getPaymentData()->getAdditionalInformation('cc_token') || !isset($ccType))
        //return '';

        if ($this->getPaymentData()->getCcType()) {

            list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

            if ($provider === "Braspag") {
                $availableTypes = explode(',', $this->getConfig()->getCcTypes());

                foreach ($availableTypes as $key => $availableType) {
                    $typeDetail = explode("-", $availableType);
                    if (isset($typeDetail[1]) && $typeDetail[1] == $brand) {
                        return $typeDetail[0];
                    }
                }

                if ($this->getConfig()->getIsTestEnvironment()) {
                    return "Simulado";
                }

                return "";
            }

            return $provider;
        }

        return "";
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

        if ($this->getCardType() == 'two_card')
            $installments = $this->cardTwo->getData('cc_installments');

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
    public function getPaymentDoSplit()
    {
        return (bool) $this->getConfig()->isPaymentSplitActive();
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
        return $this->getCardType() == 'two_card' ?  $this->cardTwo->getData('cc_number') :  $this->getPaymentData()->getCcNumber();
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardHolder()
    {
        return $this->getCardType() == 'two_card' ?  $this->cardTwo->getData('cc_owner') :  $this->getPaymentData()->getCcOwner();
    }

    /**
     * @return string
     */
    public function getPaymentCreditCardExpirationDate()
    {
        $this->getCardType() == 'two_card' ? $ccExpMonth  = $this->cardTwo->getData('cc_exp_month') :  $ccExpMonth = $this->getPaymentData()->getCcExpMonth();

        $this->getCardType() == 'two_card' ? $ccExpYear  =  $this->cardTwo->getData('cc_exp_year') :  $ccExpYear = $this->getPaymentData()->getCcExpYear();

        return str_pad($ccExpMonth, 2, '0', STR_PAD_LEFT) . '/' . $ccExpYear;
    }

    /**
     * @return mixed
     */
    public function getPaymentCreditCardSecurityCode()
    {
        return $this->getCardType() == 'two_card' ? $this->cardTwo->getData('cc_cid') :  $this->getPaymentData()->getCcCid();
    }

    /**
     * @return bool
     */
    public function getPaymentCreditCardSaveCard()
    {
        return (bool) $this->getPaymentData()->getAdditionalInformation('cc_savecard');
    }

    /**
     * @return string
     */
    public function getPaymentCreditCardBrand()
    {
        $ccType =  $this->getPaymentData()->getCcType();

        if ($this->getPaymentData()->getAdditionalInformation('cc_token') || $this->cardTwo->getData('cc_token'))
            return null;

        if ($this->getCardType() == 'two_card')
            $ccType = $this->cardTwo->getData('cc_type');

        list($provider, $brand) = array_pad(explode('-', $ccType, 2), 2, null);

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
        return $this->getPaymentData()->getAdditionalInformation('authentication_reference_id');
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
     * @param RequestPaymentSplitLibInterface $paymentSplitRequest
     * @return $this
     */
    public function setPaymentSplitRequest(RequestPaymentSplitLibInterface $paymentSplitRequest)
    {
        $this->paymentSplitRequest = $paymentSplitRequest;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitRequest()
    {
        return $this->paymentSplitRequest;
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
        return $this->getCardType() == 'two_card' ?  $this->cardTwo->getData('cc_token') :   $this->getPaymentData()->getAdditionalInformation('cc_token');
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


    protected function calcPriceWithInterest($i, $total, $interestRate)
    {
        $price = $total * $interestRate / (1 - (1 / pow((1 + $interestRate), $i)));
        return sprintf("%01.2f", $price);
    }
}

