<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

interface ConfigInterface
{
	public function getMerchantId();

	public function getMerchantKey();

	public function getPaymentDemonstrative();

	public function getPaymentInstructions();
}