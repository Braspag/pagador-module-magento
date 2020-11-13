<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Model\Payment\Info;


use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Response\BaseHandler as ResponseHandler;

class Boleto
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
            throw new \InvalidArgumentException;
        }

        return $this->getOrder()->getPayment();
    }

    /**
     * @return string
     */
    public function getBoletoUrl()
    {
        return $this->getPayment()->getAdditionalInformation(ResponseHandler::ADDITIONAL_INFORMATION_BOLETO_URL);
    }
}
