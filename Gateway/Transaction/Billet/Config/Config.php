<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;

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
}
