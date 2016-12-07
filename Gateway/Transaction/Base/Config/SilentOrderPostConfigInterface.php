<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

interface SilentOrderPostConfigInterface
{
	CONST CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_IS_ACTIVE = 'payment/%s/silentorderpost_active';
	CONST CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_HOMOLOG = 'payment/%s/silentorderpost_url_homolog';
	CONST CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_PRODUCTION = 'payment/%s/silentorderpost_url_production';

	public function isActive();

	public function getUrl();

}
