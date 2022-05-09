<?php
/**
 * Capture Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Avs;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Payment\Gateway\Data\Order\OrderAdapter;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as BraspaglibRequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Magento\Payment\Model\InfoInterface;

class Request implements BraspaglibRequestInterface, BraspagMagentoRequestInterface
{
    protected $config;
    protected $orderAdapter;
    protected $paymentData;
    protected $requestItemFactory;
    protected $billingAddress;
    protected $shippingAddress;
    protected $quote;
    protected $fingerPrintId;
    protected $session;
    protected $storeId;

    public function __construct(SessionManagerInterface $session)
    {
        $this->setSession($session);
    }

    public function getCpf()
    {
        return $this->getQuote()->getCustomerTaxvat();
    }

    public function getZipCode()
    {
        return preg_replace('/[^0-9]/','', $this->getBillingAddress()->getPostcode());
    }

    public function getStreet()
    {
        list($street,  $streetNumber) = array_pad(explode(',', $this->getBillingAddress()->getStreetLine1(), 2), 2, null);

        return trim($street);
    }

    public function getNumber()
    {
        list($street,  $streetNumber) = array_pad(explode(',', $this->getShippingAddress()->getStreetLine1(), 2), 2, null);

        return (int) $streetNumber;
    }

    public function getComplement()
    {
        return null;
    }

    public function getDistrict()
    {
        return $this->getShippingAddress()->getStreetLine2();
    }

    public function setOrderAdapter(OrderAdapterInterface $orderAdapter)
    {
        $this->orderAdapter = $orderAdapter;
        return $this;
    }

    /**
     * @param InfoInterface $payment
     */
    public function setPaymentData(InfoInterface $payment)
    {
        $this->paymentData = $payment;
    }

    /**
     * @return InfoInterface
     */
    protected function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * @return OrderAdapter
     */
    protected function getOrderAdapter()
    {
        return $this->orderAdapter;
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
    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return \Magento\Payment\Gateway\Data\AddressAdapterInterface|null
     */
    protected function getShippingAddress()
    {
        if (!$this->shippingAddress) {
            $this->shippingAddress = $this->getOrderAdapter()->getShippingAddress();
        }

        return $this->shippingAddress;
    }

    /**
     * @return \Magento\Payment\Gateway\Data\AddressAdapterInterface|null
     */
    protected function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->billingAddress = $this->getOrderAdapter()->getBillingAddress();
        }

        return $this->billingAddress;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (! $this->quote) {
            $this->quote =  $this->getSession()->getQuote();
        }

        return $this->quote;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeId = null)
    {
        $this->storeId = $storeId;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->storeId;
    }
}
