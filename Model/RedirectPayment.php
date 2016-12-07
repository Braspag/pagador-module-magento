<?php

namespace Webjump\BraspagPagador\Model;

use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Webjump\BraspagPagador\Api\RedirectPaymentInterface;
use Magento\Framework\DataObject;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RedirectPayment implements RedirectPaymentInterface
{
	protected $orderPaymentRepository;

    public function __construct(
    	OrderPaymentRepositoryInterface $orderPaymentRepository
    ){
        $this->setOrderPaymentRepository($orderPaymentRepository);
    }

    public function getLink($orderId)
    {
    	$orderPayment = $this->getOrderPaymentRepository()->get((int) $orderId);

    	$additionalInformation = $orderPayment->getAdditionalInformation();
        if (!is_object($additionalInformation)) {
            $additionalInformation = new DataObject($additionalInformation ?: []);
        }

    	return $additionalInformation->getRedirectUrl();
    }

    protected function getOrderPaymentRepository()
    {
        return $this->orderPaymentRepository;
    }

    protected function setOrderPaymentRepository($orderPaymentRepository)
    {
        $this->orderPaymentRepository = $orderPaymentRepository;

        return $this;
    }
}