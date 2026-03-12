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
use Psr\Log\LoggerInterface;

/**
 * Class PaymentStatusChangedCommand
 * @package Braspag\BraspagPagador\Model
 */
class PaymentStatusChangedCommand
{
    const MAX_RETRIES = 3;
    const RETRY_DELAY_SECONDS = 10;

    /**
     * @var Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface
     **/
    protected $config;

    /**
     * @var PaymentManager
     */
    protected $paymentManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        PaymentManager $paymentManager,
        LoggerInterface $logger
    ) {
        $this->setPaymentManager($paymentManager);
        $this->setConfig($config);
        $this->logger = $logger;
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
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(string $paymentId)
    {
        $paymentInfo = null;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            $paymentInfo = $this->getPaymentManager()->getPaymentInfo($paymentId);

            if (isset($paymentInfo['paymentType'])) {
                if ($attempt > 1) {
                    $this->logger->info(
                        "Braspag Notification: PaymentId {$paymentId} found on attempt {$attempt}"
                    );
                }
                break;
            }

            $this->logger->warning(
                "Braspag Notification: PaymentId {$paymentId} not found on attempt {$attempt}/" . self::MAX_RETRIES
            );

            if ($attempt < self::MAX_RETRIES) {
                sleep(self::RETRY_DELAY_SECONDS);
            }
        }

        if (!isset($paymentInfo['paymentType'])) {
            $this->logger->error(
                "Braspag Notification: PaymentId {$paymentId} not found after " . self::MAX_RETRIES . " attempts. "
                . "Throwing exception so Braspag can retry the notification."
            );
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Payment not found for PaymentId: %1', $paymentId)
            );
        }

        $paymentType = $paymentInfo['paymentType'];
        $braspagPaymentData = $paymentInfo['paymentInfo'];
        $magentoPaymentData = $paymentInfo['orderPayment'];

        $paymentStatus = $braspagPaymentData->getPaymentStatus();

        $this->logger->info(
            "Braspag Notification: Processing PaymentId {$paymentId} - Type: {$paymentType}, Status: {$paymentStatus}"
        );

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

        $this->logger->warning(
            "Braspag Notification: PaymentId {$paymentId} - Unhandled payment status: {$paymentStatus} for type: {$paymentType}"
        );

        return true;
    }
}
