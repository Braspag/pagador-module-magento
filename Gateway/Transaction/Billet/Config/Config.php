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
		return 'BC5D3432-527F-40C6-84BF-C549285536BE';
	}

	public function getMerchantKey()
	{
		return 'yv3hzQuDfcUnNxcgkUifz4EQVPeeAwfedilpROwn';
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

	public function getExpirationDate()
	{
		return '2016-01-03';
	}

	public function getPaymentProvider()
	{
		return 'Simulado';
	}
}
