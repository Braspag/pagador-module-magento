<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

interface SilentOrderPostConfigInterface
{
	public function isActive();

	public function getUrl();

}
