<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Braspag Transaction Boleto Config
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Config extends BaseConfig implements ConfigInterface
{

	public function getPaymentDemonstrative()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_DEMONSTRATIVE);
	}

	public function getPaymentInstructions()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_INSTRUCTIONS);
	}

    public function getPaymentIdentification()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_IDENTIFICATION);
    }

	public function getPaymentAssignor()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_ASSIGNOR);
	}

    public function getPaymentAssignorAddress()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_ASSIGNOR_ADDRESS);
    }

    public function getExpirationDate()
	{
		return $this->getDateTime()->gmDate(self::DATE_FORMAT, strtotime(sprintf(self::DAY_FORMAT, (int) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_EXPIRATION_DATE))));
	}

	public function getPaymentProvider()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PROVIDER);
	}

	public function getPaymentBank()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_BANK);
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

    public function getIdentityAttributeCode()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CUSTOMER_IDENTITY_ATTRIBUTE_CODE);
    }

    public function isPaymentSplitActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT);
    }

    public function getPaymentSplitType()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TYPE);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomatically()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS);
    }

    public function getPaymentSplitDefaultMrd()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_DEFAULT_MDR);
    }

    public function getPaymentSplitDefaultFee()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_DEFAULT_FEE);
    }
}
