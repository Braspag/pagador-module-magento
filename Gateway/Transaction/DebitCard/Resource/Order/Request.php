<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order;

use Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface as BraspaglibRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
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

    protected $helperData;

    /**
     * @var
     */
    protected $antiFraudRequest;

    /**
     * @var
     */
    protected $paymentSplitRequest;

    public function __construct(
        ConfigInterface $config,
        \Webjump\BraspagPagador\Helper\Data $helperData
    ){
        $this->setConfig($config);
        $this->helperData = $helperData;
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
        $customerName = $this->getBillingAddress()->getFirstname() . ' ' . $this->getBillingAddress()->getLastname();
    	return $this->helperData->removeSpecialCharacters($customerName);
    }

    public function getPaymentAmount()
    {
        $amount = $this->getOrderAdapter()->getGrandTotalAmount() * 100;

        return str_replace('.', '', $amount);
    }

    public function getPaymentProvider()
    {
        List($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

        if ($provider === "Braspag") {
            $availableTypes = explode(',', $this->getConfig()->getDcTypes());

            foreach($availableTypes as $key => $availableType) {
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

    /**
     * @return bool
     */
    public function getPaymentDoSplit()
    {
        return (bool) $this->getConfig()->isPaymentSplitActive();
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

    public function getPaymentCreditSoptpaymenttoken()
    {
        return $this->getPaymentData()->getAdditionalInformation('cc_soptpaymenttoken');
    }

    public function getPaymentCreditCardBrand()
    {
        list($provider, $brand) = array_pad(explode('-', $this->getPaymentData()->getCcType(), 2), 2, null);

        return ($brand) ? $brand : 'Visa';
    }

    public function getPaymentCreditCardSaveCard()
    {
        return (boolean) $this->getPaymentData()->getAdditionalInformation('cc_savecard');
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

    /**
     * @return bool
     */
    public function getPaymentAuthenticate()
    {
        return (bool) $this->getConfig()->isAuth3Ds20Active()
            && $this->getPaymentExternalAuthenticationFailureType() != ConfigInterface::BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED;
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
}
