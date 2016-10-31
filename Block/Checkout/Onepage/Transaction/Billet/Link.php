<?php

namespace Webjump\BraspagPagador\Block\Checkout\Onepage\Transaction\Billet;


use \Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\ResponseHandler;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use \Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Model\Order;

class Link extends Template
{
    private $checkoutSession;

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
    public function getCheckoutSession()
    {
       return $this->checkoutSession;
    }

    /**
     * @return Order
     */
    public function getLastOrder()
    {
        if (! ($this->checkoutSession->getLastRealOrder()) instanceof Order) {
            throw new \InvalidArgumentException;
        }

        return $this->checkoutSession->getLastRealOrder();
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderPaymentInterface|mixed|null
     */
    public function getPayment()
    {
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
