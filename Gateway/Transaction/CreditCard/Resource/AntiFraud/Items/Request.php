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

    /**
     * @param OrderItemInterface $itemAdapter
     * @param SessionManagerInterface $session
     */
    public function __construct(OrderItemInterface $itemAdapter, SessionManagerInterface $session)
    {
        $this->setItemAdapter($itemAdapter);
        $this->setSession($session);
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

    public function getPassengerEmail()
    {
        return $this->getQuote()->getCustomerEmail();
    }

    public function getPassengerIdentity()
    {
        return $this->getQuote()->getCustomerTaxvat();

    }

    public function getPassengerName()
    {
        return $this->getQuote()->getCustomerFirstname() . ' ' . $this->getQuote()->getCustomerLastname();

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
