<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model\Notification;

use Webjump\BraspagPagador\Model\PaymentManager;
use \Webjump\BraspagPagador\Api\NotificationManagerInterface;

class AntifraudStatusChangedCommand
{
    /**
     * @var Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface
     **/
    protected $config;

    /**
     * @var PaymentManager
     */
    protected $paymentManager;

    public function __construct(
        \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        PaymentManager $paymentManager
    ){
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

        if (in_array($paymentFraudAnalysis->getStatus(), $this->getStatusesToAvoidOrderStatusUpdate())){
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
