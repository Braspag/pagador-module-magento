<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Response\BaseHandler as ResponseHandler;

class Pix
{
    protected $order;

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return OrderPaymentInterface
     */
    public function getPayment()
    {
        if (! ($this->getOrder()->getPayment()) instanceof OrderPaymentInterface) {
            throw new \InvalidArgumentException();
        }

        return $this->getOrder()->getPayment();
    }

    /**
     * @return string
     */
    public function getPixUrl()
    {
        return $this->getPayment()->getAdditionalInformation(ResponseHandler::ADDITIONAL_INFORMATION_PIX_URL);
    }
}