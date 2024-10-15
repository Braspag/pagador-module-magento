<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;
use Braspag\BraspagPagador\Gateway\Transaction\AntiFraud\Config\ConfigInterface as AntiFraudConfigInterface;
use Magento\Payment\Model\Method\AbstractMethod;

class Config extends BaseConfig implements ConfigInterface
{
    public function isTwoCardActive()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_TWO_CARD_ACTIVE);
    }


    public function isInstallmentsRulesActive()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_INSTALLMENTS_RULES_ACTIVE);
    }

     /**
     * @return array|mixed
     */
    public function getInstallmentsCardsRules(): array
    {
        $arrayRules = [];
        $arrayRulesConfig = $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_INSTALLMENTS_RULES);

        if (isset($arrayRulesConfig)) {
            $arrayRulesConfig = json_decode($arrayRulesConfig, true);
            foreach ($arrayRulesConfig as $rule) {
               
                if(isset($rule['types']))
                $arrayRules[] = $rule;
            }
        }

        return $arrayRules;
    }
 
    public function isAuthorizeAndCapture()
    {
        return (AbstractMethod::ACTION_AUTHORIZE_CAPTURE === $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENT_ACTION));
    }

    public function getCcTypes()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CCTYPES);
    }

    public function getSoftDescriptor()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SOFT_ACTION);
    }

    public function hasAntiFraud()
    {
        return $this->_getConfig(AntiFraudConfigInterface::XML_PATH_ACTIVE);
    }

    public function hasAvs()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AVS_ACTIVE);
    }

    public function getReturnUrl()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_RETURN_URL);
    }

    public function isSaveCardActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SAVECARD_ACTIVE);
    }

    public function isCreateInvoiceOnNotificationCaptured()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREATE_INVOICE_NOTIFICATION_CAPTURE);
    }

    public function getCustomerStreetAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE);
    }

    public function getCustomerNumberAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE);
    }

    public function getCustomerComplementAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE);
    }

    public function getCustomerDistrictAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE);
    }

    public function getDecimalGrandTotal()
    {
        if (!( $config = $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_DECIMAL_GRAND_TOTAL) )) {
            return self::DEFAULT_DECIMAL_GRAND_TOTAL;
        }

        return $config;
    }

    public function isAuth3Ds20Active()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20);
    }

    public function isAuth3Ds20MCOnlyNotifyActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MASTERCARD_ONLY_NOTIFY);
    }

    public function isAuth3Ds20AuthorizedOnError()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_ERROR);
    }

    public function isAuth3Ds20AuthorizedOnFailure()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_FAILURE);
    }

    public function isAuth3Ds20AuthorizeOnUnenrolled()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_UNENROLLED);
    }

    public function isAuth3Ds20AuthorizeOnUnsupportedBrand()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_UNSUPPORTED_BRAND);
    }

    public function getAuth3Ds20Mdd1()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD1);
    }

    public function getAuth3Ds20Mdd2()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD2);
    }

    public function getAuth3Ds20Mdd3()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD3);
    }

    public function getAuth3Ds20Mdd4()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD4);
    }

    public function getAuth3Ds20Mdd5()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD5);
    }

    public function isPaymentSplitActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT);
    }

    public function getPaymentSplitType()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TYPE);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomatically()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS);
    }

    public function getPaymentSplitDefaultMrd()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_DEFAULT_MDR);
    }

    public function getPaymentSplitDefaultFee()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_DEFAULT_FEE);
    }

    public function isCardViewActive()
    {
        return (bool) $this->_getConfig(self::BRASPAG_PAGADOR_CREDITCARD_CARD_VIEW);
    }

    public function isAutoDisablePreviousCardsToken()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SAVECARD_DISABLE_PREVIOUSLY_USED_CARDS);
    }


}
