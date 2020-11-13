<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config;

/**
 * Interface ConfigInterface
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEVENDOR = 'webjump_braspag/paymentsplit_marketplacevendor/marketplacevendor';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_MERCHANT_ID = 'webjump_braspag/paymentsplit_marketplacecredentials/merchantid';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_ID = 'webjump_braspag/paymentsplit_marketplacecredentials/clientid';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_SECRET = 'webjump_braspag/paymentsplit_marketplacecredentials/clientsecret';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_TYPE = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_type';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_PERCENT = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_percent';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_FIXED_VALUE = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_fixed_value';

    public function getPaymentSplitMarketPlaceVendor();

    public function getPaymentSplitMarketPlaceCredendialsMerchantId();

    public function getPaymentSplitMarketPlaceCredendialsClientId();

    public function getPaymentSplitMarketPlaceCredendialsClientSecret();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipation();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationType();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationPercent();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationFixedValue();
}
