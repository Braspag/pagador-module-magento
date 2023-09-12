<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Braspag\BraspagPagador\Model\Notification;

use Braspag\BraspagPagador\Model\PaymentManager;
use Braspag\BraspagPagador\Api\NotificationManagerInterface;

/**
 * Class PaymentStatusChangedCommand
 * @package Braspag\BraspagPagador\Model
 */
class PaymentStatusChangedCommand
{
    /**
     * @var Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface
     **/
    protected $config;

    /**
     * @var PaymentManager
     */
    protected $paymentManager;

    public function __construct(
        \Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        PaymentManager $paymentManager
    ) {
        $this->setPaymentManager($paymentManager);
        $this->setConfig($config);
    }

    /**
     * @return mixed
     */
    public function getPaymentManager()
    {
        return $this->paymentManager;
    }

    /**
     * @param mixed $paymentManager
     */
    public function setPaymentManager($paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $paymentId
     * @return bool
     */
    public function execute(string $paymentId)
    {
        $paymentInfo = $this->getPaymentManager()->getPaymentInfo($paymentId);

        if(!isset($paymentInfo['paymentType']))
         return true;

        $paymentType = $paymentInfo['paymentType'];
        $braspagPaymentData = $paymentInfo['paymentInfo'];
        $magentoPaymentData = $paymentInfo['orderPayment'];

        $paymentStatus = $braspagPaymentData->getPaymentStatus();

        // 2 = capture
        if ($paymentType == 'boleto' && $paymentStatus == 2) {
            return $this->getPaymentManager()->registerCapturedPayment($braspagPaymentData, $magentoPaymentData, true);
        }

        // 2 = capture
        if ($paymentType == 'creditCard' && $paymentStatus == 2) {
            $createInvoice = $this->getConfig()->isCreateInvoiceOnNotificationCaptured();

            return $this->getPaymentManager()
                ->registerCapturedPayment($braspagPaymentData, $magentoPaymentData, $createInvoice);
        }

         // 2 = capture
         if ($paymentType == 'pix' && $paymentStatus == 2) {
            return $this->getPaymentManager()->registerCapturedPayment($braspagPaymentData, $magentoPaymentData, true);
         }


        // 3 = Denied/10 = Voided/13 = Aborted
        if (in_array($paymentStatus, [3, 10, 13])) {
            return $this->getPaymentManager()->registerCanceledPayment($braspagPaymentData, $magentoPaymentData, true);
        }

        // 11 = Refunded
        if ($paymentStatus == 11) {
            return $this->getPaymentManager()->registerRefundedPayment($braspagPaymentData, $magentoPaymentData, true);
        }

        return true;
    }
}
