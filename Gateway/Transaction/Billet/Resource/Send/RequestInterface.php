<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

interface RequestInterface
{
	public function setPayment(PaymentDataObjectInterface $paymentDataObject);
}