<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Braspag\BraspagPagador\Model;

use Psr\Log\LoggerInterface;
use Braspag\BraspagPagador\Api\NotificationManagerInterface;
use Braspag\BraspagPagador\Model\Notification\AntifraudStatusChangedCommand;
use Braspag\BraspagPagador\Model\Notification\PaymentStatusChangedCommand;

class NotificationManager implements NotificationManagerInterface
{
    /**
     * @var LoggerInterface
     **/
    protected $logger;

    /**
     * @var AntifraudStatusChangedCommand
     */
    protected $antifraudStatusChangedCommand;

    /**
     * @var PaymentStatusChangedCommand
     */
    protected $paymentStatusChangedCommand;

    /**
     * NotificationManager constructor.
     * @param LoggerInterface $logger
     * @param AntifraudStatusChangedCommand $antifraudStatusChangedCommand
     * @param PaymentStatusChangedCommand $paymentStatusChangedCommand
     */
    public function __construct(
        LoggerInterface $logger,
        AntifraudStatusChangedCommand $antifraudStatusChangedCommand,
        PaymentStatusChangedCommand $paymentStatusChangedCommand
    ) {
        $this->setLogger($logger);
        $this->setAntifraudStatusChangedCommand($antifraudStatusChangedCommand);
        $this->setPaymentStatusChangedCommand($paymentStatusChangedCommand);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getAntifraudStatusChangedCommand()
    {
        return $this->antifraudStatusChangedCommand;
    }

    /**
     * @param mixed $antifraudStatusChangedCommand
     */
    public function setAntifraudStatusChangedCommand($antifraudStatusChangedCommand)
    {
        $this->antifraudStatusChangedCommand = $antifraudStatusChangedCommand;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatusChangedCommand()
    {
        return $this->paymentStatusChangedCommand;
    }

    /**
     * @param mixed $paymentStatusChangedCommand
     */
    public function setPaymentStatusChangedCommand($paymentStatusChangedCommand)
    {
        $this->paymentStatusChangedCommand = $paymentStatusChangedCommand;
    }

    /**
     * @param string $PaymentId
     * @param int $ChangeType
     * @param string $RecurrentPaymentId
     * @return bool
     */
    public function save($PaymentId, $ChangeType, $RecurrentPaymentId = '')
    {
        $this->getLogger()->info("Braspag Notification Received:
            - PaymentId {$PaymentId}
            - ChangeType {$ChangeType}
            - RecurrentPaymentId {$RecurrentPaymentId}");

        try {
            if ($ChangeType == self::NOTIFICATION_PAYMENT_STATUS_CHANGED) {
                $result = $this->getPaymentStatusChangedCommand()->execute($PaymentId);
                $this->getLogger()->info(
                    "Braspag Notification: PaymentId {$PaymentId} processed successfully. Result: " . ($result ? 'true' : 'false')
                );
                return $result;
            }

            if ($ChangeType == self::NOTIFICATION_ANTI_FRAUD_STATUS_CHANGED) {
                $result = $this->getAntifraudStatusChangedCommand()->execute($PaymentId);
                $this->getLogger()->info(
                    "Braspag Notification: PaymentId {$PaymentId} antifraud processed. Result: " . ($result ? 'true' : 'false')
                );
                return $result;
            }

            $this->getLogger()->warning(
                "Braspag Notification: PaymentId {$PaymentId} - Unhandled ChangeType: {$ChangeType}"
            );
        } catch (\Exception $e) {
            $this->getLogger()->error(
                "Braspag Notification: PaymentId {$PaymentId} - Error: " . $e->getMessage()
            );
            throw $e;
        }

        return false;
    }
}