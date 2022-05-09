<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config;

/**
 * Interface ConfigInterface
 * @package Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEVENDOR = 'webjump_braspag/paymentsplit_marketplacevendor/marketplacevendor';

    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEVENDOR_PAYMENT_TYPES_TO_APPLY_BRASPAG_COMMISSION = 'webjump_braspag/paymentsplit_marketplacewebkul/marketplacewebkul_payment_types_to_apply_braspag_commission';

    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_MERCHANT_ID = 'webjump_braspag/paymentsplit_marketplacecredentials/merchantid';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_ID = 'webjump_braspag/paymentsplit_marketplacecredentials/clientid';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACECREDENTIALS_CLIENT_SECRET = 'webjump_braspag/paymentsplit_marketplacecredentials/clientsecret';

    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_TYPE = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_type';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_PERCENT = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_percent';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_SALES_PARTICIPATION_FIXED_VALUE = 'webjump_braspag/paymentsplit_marketplacegeneral/markeplace_sales_participation_fixed_value';

    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_BRASPAG_FINANCIAL_PAGE_ENABLED = 'webjump_braspag/paymentsplit_marketplacegeneral/marketplace_braspag_financial_page_enabled';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_PAYMENTSPLIT_DISCOUNT_TYPE = 'webjump_braspag/paymentsplit_marketplacegeneral/paymentsplit_discount_type';

    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_PAYMENTSPLIT_MDR_TYPE = 'webjump_braspag/paymentsplit_marketplacegeneral/paymentsplit_mdr_type';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_PAYMENTSPLIT_MDR_UNIQUE = 'webjump_braspag/paymentsplit_marketplacegeneral/paymentsplit_mdr_unique';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_PAYMENTSPLIT_MDR_MULTIPLE = 'webjump_braspag/paymentsplit_marketplacegeneral/paymentsplit_mdr_multiple';
    const CONFIG_XML_BRASPAG_PAYMENTSPLIT_MARKETPLACEGENERAL_PAYMENTSPLIT_FEE = 'webjump_braspag/paymentsplit_marketplacegeneral/paymentsplit_fee';

    public function getPaymentSplitMarketPlaceVendor();

    public function getPaymentSplitMarketPlaceVendorPaymentTypesToApplyBraspagCommission();

    public function getPaymentSplitMarketPlaceCredendialsMerchantId();

    public function getPaymentSplitMarketPlaceCredendialsClientId();

    public function getPaymentSplitMarketPlaceCredendialsClientSecret();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipation();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationType();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationPercent();

    public function getPaymentSplitMarketPlaceGeneralSalesParticipationFixedValue();

    public function getPaymentSplitMarketPlaceGeneralBraspagFinancialPageEnabled();

    public function getPaymentSplitMarketPlaceGeneralPaymentSplitDiscountType();

    public function getPaymentSplitMarketPlaceGeneralPaymentSplitMdrType();

    public function getPaymentSplitMarketPlaceGeneralPaymentSplitMdrUnique();

    public function getPaymentSplitMarketPlaceGeneralPaymentSplitMdrMultiple();

    public function getPaymentSplitMarketPlaceGeneralPaymentSplitFee();
}
