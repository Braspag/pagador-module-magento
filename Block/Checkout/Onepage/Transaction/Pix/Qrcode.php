<?php

/**
 * Braspag Block Transaction Pix Qrcode
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */

namespace Braspag\BraspagPagador\Block\Checkout\Onepage\Transaction\Pix;

use DateInterval;
use Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Response\BaseHandler;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\Data\OrderInterface as Order;
use Magento\Sales\Api\Data\OrderPaymentInterface as Payment;
use Braspag\BraspagPagador\Helper\Pix as HelperPix;
use Braspag\BraspagPagador\Gateway\Transaction\Pix\Config\ConfigInterface;

class Qrcode extends Template
{
    protected $checkoutSession;

    protected $config;

    /**
     * Locale Date/Timezone
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * Link constructor.
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        ConfigInterface $configInterface,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->config = $configInterface;
        $this->timezone = $timezone;
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
            throw new \InvalidArgumentException();
        }

        return $this->checkoutSession->getLastRealOrder();
    }

    public function getOrder()
    {
        return $this->getLastOrder();
    }

    /**
     * @return Payment
     */
    protected function getPayment()
    {
        if (! ($this->getLastOrder()->getPayment()) instanceof Payment) {
            throw new \InvalidArgumentException();
        }

        return $this->getLastOrder()->getPayment();
    }

    public function shouldDisplay()
    {
        return $this->getPayment()->getMethod() == \Braspag\BraspagPagador\Model\Method\Pix\Ui\ConfigProvider::CODE;
    }

    public function getQrCodeSrc()
    {
        return $this->getPayment()->getAdditionalInformation(\Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send\Response\BaseResponseHandler::ADDITIONAL_INFORMATION_PIX_QRCODESTRING);
    }

    public function getQrCode()
    {
        return $this->getPayment()->getAdditionalInformation(\Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send\Response\BaseResponseHandler::ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE);
    }

    /**
     * @return string
     */
    public function getPixQrCode()
    {
        return $this->getPayment()->getAdditionalInformation(BaseHandler::ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE);
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getPayment()->getAdditionalInformation(BaseHandler::ADDITIONAL_INFORMATION_PIX_TRANSACTIONID);
    }


    public function getExpirationTime()
    {
        $now = new \DateTime('now');

        $pixExpiration = $this->config->getExpirationDate();

        $minutos = (null == $pixExpiration ? '86400' : $pixExpiration);
        $minExpiration = 'PT' . $minutos . 'M';

        $now->add(new \DateInterval($minExpiration));
        $now->setTimezone(new \DateTimeZone($this->timezone->getConfigTimezone('store')));

        return $now->format("d-m-Y h:i:s");
    }
}