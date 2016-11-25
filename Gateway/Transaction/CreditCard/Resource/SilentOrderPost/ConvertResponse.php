<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\SilentOrderPost;

class ConvertResponse implements \Magento\Payment\Gateway\Http\ConverterInterface
{
    public function convert($source)
    {
    	return new SilentOrderPost(json_decode($source, true));
    }
}
