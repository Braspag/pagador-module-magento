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
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_ASSIGNOR = 'payment/braspag_pagador_billet/assignor';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_EXPIRATION_DATE = 'payment/braspag_pagador_billet/expiration_days';
	const CONFIG_XML_BRASPAG_PAGADOR_BILLET_PROVIDER = 'payment/braspag_pagador_billet/types';

	const DAY_FORMAT = '+%s day';
	
	public function getMerchantId();

	public function getMerchantKey();

	public function getPaymentDemonstrative();

	public function getPaymentInstructions();

	public function getPaymentAssignor();

	public function getExpirationDate();

	public function getPaymentProvider();
}