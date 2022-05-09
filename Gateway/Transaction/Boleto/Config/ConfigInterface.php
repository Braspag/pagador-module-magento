<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config;

/**
 * Braspag Transaction Boleto Config Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface ConfigInterface extends \Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_DEMONSTRATIVE = 'payment/braspag_pagador_boleto/demonstrative';
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_INSTRUCTIONS = 'payment/braspag_pagador_boleto/instructions';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_IDENTIFICATION = 'payment/braspag_pagador_boleto/identification';
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_ASSIGNOR = 'payment/braspag_pagador_boleto/assignor';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_ASSIGNOR_ADDRESS = 'payment/braspag_pagador_boleto/assignor_address';
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_EXPIRATION_DATE = 'payment/braspag_pagador_boleto/expiration_days';
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PROVIDER = 'payment/braspag_pagador_boleto/types';
	const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_BANK = 'payment/braspag_pagador_boleto/bank';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE = 'payment/braspag_pagador_customer_address/street_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE = 'payment/braspag_pagador_customer_address/number_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/complement_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/district_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CUSTOMER_IDENTITY_ATTRIBUTE_CODE = 'payment/braspag_pagador_creditcard/customer_identity_attribute_code';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT = 'payment/braspag_pagador_boleto/paymentsplit';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TYPE = 'payment/braspag_pagador_boleto/paymentsplit_type';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY = 'payment/braspag_pagador_boleto/paymentsplit_transactional_post_send_request_automatically';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS = 'payment/braspag_pagador_boleto/paymentsplit_transactional_post_send_request_automatically_after_x_hours';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_DEFAULT_MDR = 'payment/braspag_pagador_boleto/paymentsplit_mdr';
    const CONFIG_XML_BRASPAG_PAGADOR_BOLETO_PAYMENTSPLIT_DEFAULT_FEE = 'payment/braspag_pagador_boleto/paymentsplit_fee';
	const DAY_FORMAT = '+%s day';

	public function getPaymentDemonstrative();

	public function getPaymentInstructions();

	public function getPaymentIdentification();

	public function getPaymentAssignor();

    public function getPaymentAssignorAddress();

	public function getExpirationDate();

	public function getPaymentProvider();

    public function getPaymentBank();

    public function getCustomerStreetAttribute();

    public function getCustomerNumberAttribute();

    public function getCustomerComplementAttribute();

    public function getCustomerDistrictAttribute();

    public function getIdentityAttributeCode();

    public function isPaymentSplitActive();

    public function getPaymentSplitType();

    public function getPaymentSplitTransactionalPostSendRequestAutomatically();

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours();

    public function getPaymentSplitDefaultMrd();

    public function getPaymentSplitDefaultFee();
}
