<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Braspag Transaction Billet Send Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestBuilder implements BuilderInterface
{
	protected $request;

    protected $orderRepository;

	public function __construct(
		RequestInterface $request,
        OrderRepositoryInterface $orderRepository
	) {
        $this->setRequest($request);
        $this->setOrderRepository($orderRepository);
	}

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();

        $this->getRequest()->setOrderAdapter($orderAdapter);

        return $this->getRequest();
    }

    protected function getOrderRepository()
    {
        return $this->orderRepository;
    }

    protected function setOrderRepository(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;

        return $this;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }
}
