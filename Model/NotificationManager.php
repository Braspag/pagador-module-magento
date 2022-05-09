<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Psr\Log\LoggerInterface;
use \Webjump\BraspagPagador\Api\NotificationManagerInterface;
use \Webjump\BraspagPagador\Model\Notification\AntifraudStatusChangedCommand;
use \Webjump\BraspagPagador\Model\Notification\PaymentStatusChangedCommand;

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
    ){
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
        $this->getLogger()->info("Payment Notification: 
            - PaymentId {$PaymentId} 
            - ChangeType {$ChangeType} 
            - RecurrentPaymentId {$RecurrentPaymentId}"
        );

        if ($ChangeType == self::NOTIFICATION_PAYMENT_STATUS_CHANGED) {
            return $this->getPaymentStatusChangedCommand()->execute($PaymentId);
        }

        if ($ChangeType == self::NOTIFICATION_ANTI_FRAUD_STATUS_CHANGED) {
            return $this->getAntifraudStatusChangedCommand()->execute($PaymentId);
        }

        return false;
    }
}
