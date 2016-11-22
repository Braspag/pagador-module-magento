<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */
namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Items;


use Magento\Sales\Api\Data\OrderItemInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\Items\RequestInterface as BraspaglibRequestInterface;

class Request implements BraspaglibRequestInterface
{
    protected $itemAdapter;

    /**
     * @param OrderItemInterface $itemAdapter
     */
    public function __construct(OrderItemInterface $itemAdapter)
    {
        $this->setItemAdapter($itemAdapter);
    }

    /**
     * @todo to implementation
     */
    public function getGiftCategory()
    {
    }

    /**
     * @todo to implementation
     */
    public function getHostHedge()
    {
    }

    /**
     * @todo to implementation
     */
    public function getNonSensicalHedge()
    {
    }

    /**
     * @todo to implementation
     */
    public function getObscenitiesHedge()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPhoneHedge()
    {
    }

    public function getName()
    {
        return $this->getItemAdapter()->getName();
    }

    public function getQuantity()
    {
        return $this->getItemAdapter()->getQtyOrdered();
    }

    public function getSku()
    {
        return $this->getItemAdapter()->getSku();
    }

    public function getUnitPrice()
    {
        return $this->getItemAdapter()->getPrice();
    }

    /**
     * @todo to implementation
     */
    public function getRisk()
    {
    }

    /**
     * @todo to implementation
     */
    public function getTimeHedge()
    {
    }

    /**
     * @todo to implementation
     */
    public function getType()
    {
    }

    /**
     * @todo to implementation
     */
    public function getVelocityHedge()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerEmail()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerIdentity()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerName()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerRating()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerPhone()
    {
    }

    /**
     * @todo to implementation
     */
    public function getPassengerStatus()
    {
    }

    /**
     * @param OrderItemInterface $itemAdapter
     * @return $this
     */
    public function setItemAdapter(OrderItemInterface $itemAdapter)
    {
        $this->itemAdapter = $itemAdapter;
        return $this;
    }

    /**
     * @return OrderItemInterface
     */
    public function getItemAdapter()
    {
        return $this->itemAdapter;
    }
}
