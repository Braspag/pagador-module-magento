<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;

class RequestBuilder implements BuilderInterface
{
	protected $request;

	public function __construct(
		RequestInterface $request
	) {
		$this->request = $request;
	}

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $payment = $buildSubject['payment'];
        $this->request->setPayment($payment);

        return ['request' => $this->request];
    }
}
