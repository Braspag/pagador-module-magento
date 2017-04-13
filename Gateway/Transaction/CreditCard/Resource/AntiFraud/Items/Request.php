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


use Magento\Framework\Session\SessionManagerInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\Items\RequestInterface as BraspaglibRequestInterface;

class Request implements BraspaglibRequestInterface
{
    protected $itemAdapter;
    protected $session;
    protected $quote;

    public function __construct(OrderItemInterface $itemAdapter, SessionManagerInterface $session)
    {
        $this->setItemAdapter($itemAdapter);
        $this->setSession($session);
    }

    public function getGiftCategory()
    {
    }

    public function getHostHedge()
    {
    }

    public function getNonSensicalHedge()
    {
    }

    public function getObscenitiesHedge()
    {
    }

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
        $price = $this->getItemAdapter()->getPrice();

        if (! $price && $this->getItemAdapter()->getParentItem()) {
            $price = $this->getItemAdapter()->getParentItem()->getPrice();
        }

        $amount = $price * 100;

        return str_replace('.', '', $amount);
    }

    public function getRisk()
    {
    }

    public function getTimeHedge()
    {
    }

    public function getType()
    {
    }

    public function getVelocityHedge()
    {
    }

    public function getPassengerEmail()
    {
    }

    public function getPassengerIdentity()
    {
    }

    public function getPassengerName()
    {
    }

    public function getPassengerRating()
    {
    }

    public function getPassengerPhone()
    {
    }

    public function getPassengerStatus()
    {
    }

    /**
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionManagerInterface $session
     */
    protected function setSession($session)
    {
        $this->session = $session;
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

    /**
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->getSession()->getQuote();
        }

        return $this->quote;
    }

}
