<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

/**
 * Braspag Transaction Billet Config Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface ConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_DEMONSTRATIVE = 'payment/braspag_pagador_billet/demonstrative';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_INSTRUCTIONS = 'payment/braspag_pagador_billet/instructions';
    const CONFIG_XML_BRASPAG_PAGADOR_BILLET_IDENTIFICATION = 'payment/braspag_pagador_billet/identification';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_ASSIGNOR = 'payment/braspag_pagador_billet/assignor';
    const CONFIG_XML_BRASPAG_PAGADOR_BILLET_ASSIGNOR_ADDRESS = 'payment/braspag_pagador_billet/assignor_address';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_EXPIRATION_DATE = 'payment/braspag_pagador_billet/expiration_days';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_PROVIDER = 'payment/braspag_pagador_billet/types';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE = 'payment/braspag_pagador_customer_address/street_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE = 'payment/braspag_pagador_customer_address/number_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/complement_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/district_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CUSTOMER_IDENTITY_ATTRIBUTE_CODE = 'payment/braspag_pagador_creditcard/customer_identity_attribute_code';
	const DAY_FORMAT = '+%s day';
	
	public function getMerchantId();

	public function getMerchantKey();

	public function getPaymentDemonstrative();

	public function getPaymentInstructions();

	public function getPaymentIdentification();

	public function getPaymentAssignor();

    public function getPaymentAssignorAddress();

	public function getExpirationDate();

	public function getPaymentProvider();

    public function getCustomerStreetAttribute();

    public function getCustomerNumberAttribute();

    public function getCustomerComplementAttribute();

    public function getCustomerDistrictAttribute();

    public function getIdentityAttributeCode();
}