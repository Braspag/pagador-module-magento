<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

interface SilentOrderPostConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_IS_ACTIVE = 'payment/%s/silentorderpost_active';
	const CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_HOMOLOG = 'payment/%s/silentorderpost_paymenttoken_url_homolog';
	const CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_PRODUCTION = 'payment/%s/silentorderpost_paymenttoken_url_production';
	const CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_ACCESSTOKEN_URL_HOMOLOG = 'payment/%s/silentorderpost_accesstoken_url_homolog';
	const CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_ACCESSTOKEN_URL_PRODUCTION = 'payment/%s/silentorderpost_accesstoken_url_production';

	public function isActive();

	public function getPaymentTokenUrl();

	public function getAccessTokenUrl();
}
