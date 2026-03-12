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

class AntifraudStatusChangedCommand
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
     */
    public function execute(string $paymentId)
    {
        $paymentInfo = null;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            $paymentInfo = $this->getPaymentManager()->getPaymentInfo($paymentId);

            if (isset($paymentInfo['paymentType'])) {
                if ($attempt > 1) {
                    $this->logger->info(
                        "Braspag Antifraud Notification: PaymentId {$paymentId} found on attempt {$attempt}"
                    );
                }
                break;
            }

            $this->logger->warning(
                "Braspag Antifraud Notification: PaymentId {$paymentId} not found on attempt {$attempt}/" . self::MAX_RETRIES
            );

            if ($attempt < self::MAX_RETRIES) {
                sleep(self::RETRY_DELAY_SECONDS);
            }
        }

        if (!isset($paymentInfo['paymentType'])) {
            $this->logger->error(
                "Braspag Antifraud Notification: PaymentId {$paymentId} not found after " . self::MAX_RETRIES . " attempts. "
                . "Throwing exception so Braspag can retry the notification."
            );
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Payment not found for PaymentId: %1', $paymentId)
            );
        }

        $paymentType = $paymentInfo['paymentType'];

        if ($paymentType != 'creditCard') {
            return false;
        }

        $braspagPaymentData = $paymentInfo['paymentInfo'];
        $magentoPaymentData = $paymentInfo['orderPayment'];

        $paymentStatus = $braspagPaymentData->getPaymentStatus();

        $paymentFraudAnalysis = $braspagPaymentData->getPaymentFraudAnalysis();
        if (!$paymentFraudAnalysis) {
            return false;
        }

        if (in_array($paymentFraudAnalysis->getStatus(), $this->getStatusesToAvoidOrderStatusUpdate())) {
            return false;
        }

        if ($paymentFraudAnalysis->getStatus() == NotificationManagerInterface::ANTIFRAUD_STATUS_ACCEPT) {
            if ($paymentStatus == 1) {
                return $this->getPaymentManager()->registerAuthorizedPayment($braspagPaymentData, $magentoPaymentData);
            }

            if ($paymentStatus == 2) {
                return $this->getPaymentManager()->registerCapturedPayment(
                    $braspagPaymentData,
                    $magentoPaymentData,
                    $this->getConfig()->isCreateInvoiceOnNotificationCaptured()
                );
            }
        }

        if (in_array($paymentFraudAnalysis->getStatus(), $this->getStatusesToOrderCancel())) {
            return $this->getPaymentManager()->registerCanceledPayment($braspagPaymentData, $magentoPaymentData, true);
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getStatusesToAvoidOrderStatusUpdate()
    {
        return [
            NotificationManagerInterface::ANTIFRAUD_STATUS_REVIEW,
            NotificationManagerInterface::ANTIFRAUD_STATUS_PENDENT
        ];
    }

    /**
     * @return array
     */
    protected function getStatusesToOrderCancel()
    {
        return [
            NotificationManagerInterface::ANTIFRAUD_STATUS_UNFINISHED,
            NotificationManagerInterface::ANTIFRAUD_STATUS_PROVIDERERROR,
            NotificationManagerInterface::ANTIFRAUD_STATUS_REJECT
        ];
    }
}