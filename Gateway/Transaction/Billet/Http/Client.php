<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Http;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Client implements ClientInterface
{
    public function placeRequest(TransferInterface $transferObject)
    {
    	return [];
    }
}