<?php

namespace Webjump\BraspagPagador\Block\Checkout\Onepage\Transaction\Billet;


use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\ResponseHandler;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\Data\OrderInterface as Order;
use Magento\Sales\Api\Data\OrderPaymentInterface as Payment;

class Link extends Template
{
    protected $checkoutSession;

    /**
     * Link constructor.
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(Context $context, CheckoutSession $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, []);
    }

    /**
     * @return CheckoutSession
     */
    protected function getCheckoutSession()
    {
       return $this->checkoutSession;
    }

    /**
     * @return Order
     */
    protected function getLastOrder()
    {
        if (! ($this->checkoutSession->getLastRealOrder()) instanceof Order) {
            throw new \InvalidArgumentException;
        }

        return $this->checkoutSession->getLastRealOrder();
    }

    /**
     * @return Payment
     */
    protected function getPayment()
    {
        if (! ($this->getLastOrder()->getPayment()) instanceof Payment) {
            throw new \InvalidArgumentException;
        }

        return $this->getLastOrder()->getPayment();
    }

    /**
     * @return string
     */
    public function getBilletUrl()
    {
        return $this->getPayment()->getAdditionalInformation(ResponseHandler::ADDITIONAL_INFORMATION_BILLET_URL);
    }
}
