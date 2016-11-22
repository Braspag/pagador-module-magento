<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

class SilentOrderPostConfig implements SilentOrderPostConfigInterface
{
	public function isActive($code)
	{
		return true;
	}

	public function getUrl($code)
	{
		return 'https://homologacao.pagador.com.br/post/api/public/v1/card';
	}

	protected function isSandBoxMode()
	{
		return true;
	}

}
