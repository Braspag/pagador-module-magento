<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Class Config
 * @package Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config
 */
class Config extends BaseConfig implements ConfigInterface
{
    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceVendor()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEVENDOR);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceCredendialsMerchantId()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_MERCHANT_ID);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceCredendialsClientId()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_ID);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceCredendialsClientSecret()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_SECRET);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceGeneralSalesParticipation()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceGeneralSalesParticipationType()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_TYPE);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceGeneralSalesParticipationPercent()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_PERCENT);
    }

    /**
     * @return mixed
     */
    public function getPaymentSplitMarketPlaceGeneralSalesParticipationFixedValue()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_FIXED_VALUE);
    }
}
