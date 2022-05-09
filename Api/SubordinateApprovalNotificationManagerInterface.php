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
 * Interface SubordinateApprovalNotificationManagerInterface
 * @package Webjump\BraspagPagador\Api
 */
interface SubordinateApprovalNotificationManagerInterface
{
    const NOTIFICATION_SELLER_APPROVAL_STATUS_APPROVED = 'Approved';
    const NOTIFICATION_SELLER_APPROVAL_STATUS_APPROVED_WITH_RESTRICTION = 'ApprovedWithRestriction';
    const NOTIFICATION_SELLER_APPROVAL_STATUS_REJECTED = 'Rejected';

    /**
     * @param string $MerchantId
     * @param string $Status
     * @param int $Score
     * @param string $DenialReason
     * @return boolean
     */
    public function save($MerchantId, $Status, $Score, $DenialReason = null);
}
