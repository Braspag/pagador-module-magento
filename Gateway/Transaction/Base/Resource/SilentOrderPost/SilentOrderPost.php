<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost;

class SilentOrderPost extends \Magento\Framework\DataObject implements SilentOrderPostInterface
{
	const ACCESS_TOKEN = 'AccessToken';

    public function getAccessToken()
    {
    	return $this->getData(self::ACCESS_TOKEN);
    }
}
