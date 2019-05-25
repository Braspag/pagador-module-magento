<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth\Config;

/**
 * Interface ConfigInterface
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth\Config
 */
interface ConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_NAME    = 'webjump_braspag/pagador/merchant_name';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT    = 'webjump_braspag/pagador/test_mode';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_ESTABLISHMENT_CODE    = 'webjump_braspag/pagador/establishment_code';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MMC    = 'webjump_braspag/pagador/mcc';
    const CONFIG_BRASPAG_PAGADOR_GLOBAL_AUTHENTICATION_TOKEN  = 'webjump_braspag/auth/token';
	const DATE_FORMAT = 'Y-m-d';

    public function getAuthenticationBasicToken();

    public function getMerchantName();

    public function getEstablishmentCode();

    public function getMCC();

    public function getIsTestEnvironment();
}