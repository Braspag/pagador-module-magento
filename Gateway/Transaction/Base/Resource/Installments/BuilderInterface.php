<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

interface BuilderInterface
{
    public function build($amount , $cardType);
}
