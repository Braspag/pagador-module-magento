<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

interface SilentOrderPostConfigInterface
{
	public function isActive($code);

	public function getUrl($code);

}
