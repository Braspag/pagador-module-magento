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
interface NotificationManagementInterface
{
    /**
     * @api
     * @param string $PaymentId
     * @param int $ChangeType
     * @param string $RecurrentPaymentId
     * @return boolean
     */
    public function save($PaymentId, $ChangeType, $RecurrentPaymentId = '');
}