<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;

/**
 * Braspag Transaction Billet Config
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Config implements ConfigInterface
{
	public function getMerchantId()
	{
		return 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
	}

	public function getMerchantKey()
	{
		return '0123456789012345678901234567890123456789';
	}

	public function getPaymentDemonstrative()
	{
		return 'Desmonstrative Teste';
	}

	public function getPaymentInstructions()
	{
		return 'Aceitar somente até a data de vencimento, após essa data juros de 1% dia.';
	}

	public function getPaymentAssignor()
	{
		return 'ABC Businnes';
	}

	public function getExpirationDays()
	{
		return 3;
	}
}
