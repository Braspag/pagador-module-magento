<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost;

class ConvertResponse implements \Magento\Payment\Gateway\Http\ConverterInterface
{
    public function convert($source)
    {
    	$silentOrderPost = new SilentOrderPost;

    	if (!empty($source)) {
    		$silentOrderPost->setData((json_decode($source, true)));
    	}

    	return $silentOrderPost;
    }
}
