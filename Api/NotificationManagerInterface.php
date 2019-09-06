<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Api;

/**
 * Interface NotificationManagementInterface
 * @package Webjump\BraspagPagador\Api
 */
interface NotificationManagerInterface
{
    const NOTIFICATION_PAYMENT_STATUS_CHANGED            = 1;
    const NOTIFICATION_RECURRENCE_CREATED                = 2;
    const NOTIFICATION_ANTI_FRAUD_STATUS_CHANGED         = 3;
    const NOTIFICATION_RECURRENCE_PAYMENT_STATUS_CHANGED = 4;
    const NOTIFICATION_REFUND_DENIED                     = 5;

    /**
     * @param string $PaymentId
     * @param int $ChangeType
     * @param string $RecurrentPaymentId
     * @return boolean
     */
    public function save($PaymentId, $ChangeType, $RecurrentPaymentId = '');

    /**
     * @param string $paymentId
     * @return boolean
     */
    public function paymentStatusChanged(string $paymentId);
}
