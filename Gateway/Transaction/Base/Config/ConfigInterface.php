<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

/**
 *
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface ConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID     = 'webjump_braspag/pagador/merchant_id';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY    = 'webjump_braspag/pagador/merchant_key';
    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_NAME = 'webjump_braspag/pagador/merchant_name';
    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_ESTABLISHMENT_CODE = 'webjump_braspag/pagador/establishment_code';
    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MMC = 'webjump_braspag/pagador/mcc';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_RETURN_URL    = 'webjump_braspag/pagador/return_url';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT    = 'webjump_braspag/pagador/test_mode';
	const DATE_FORMAT = 'Y-m-d';

    public function getMerchantId();

    public function getMerchantKey();

    public function getMerchantName();

    public function getEstablishmentCode();

    public function getMCC();

    public function getIsTestEnvironment();

}
