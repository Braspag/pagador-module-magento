<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Http;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;

class TransferFactory implements TransferFactoryInterface
{
    private $transferBuilder;

    public function __construct(TransferBuilder $transferBuilder)
    {
        $this->transferBuilder = $transferBuilder;
    }

    public function create(array $request)
    {
        
    }
}