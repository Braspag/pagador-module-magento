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
use \Webjump\BraspagPagador\Api\SubordinateApprovalNotificationManagerInterface;
use \Webjump\BraspagPagador\Model\Notification\SubordinateApprovalStatusChangedCommand;

class SubordinateApprovalNotificationManager implements SubordinateApprovalNotificationManagerInterface
{
    /**
     * @var LoggerInterface
     **/
    protected $logger;

    /**
     * @var SubordinateApprovalStatusChangedCommand
     */
    protected $subordinateApprovalStatusChangedCommand;

    /**
     * SubordinateApprovalNotificationManager constructor.
     * @param LoggerInterface $logger
     * @param SubordinateApprovalStatusChangedCommand $subordinateApprovalStatusChangedCommand
     */
    public function __construct(
        LoggerInterface $logger,
        SubordinateApprovalStatusChangedCommand $subordinateApprovalStatusChangedCommand
    ){
        $this->setLogger($logger);
        $this->setSubordinateApprovalStatusChangedCommand($subordinateApprovalStatusChangedCommand);
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
     * @return SubordinateApprovalStatusChangedCommand
     */
    public function getSubordinateApprovalStatusChangedCommand()
    {
        return $this->subordinateApprovalStatusChangedCommand;
    }

    /**
     * @param SubordinateApprovalStatusChangedCommand $subordinateApprovalStatusChangedCommand
     */
    public function setSubordinateApprovalStatusChangedCommand($subordinateApprovalStatusChangedCommand)
    {
        $this->subordinateApprovalStatusChangedCommand = $subordinateApprovalStatusChangedCommand;
    }

    /**
     * @param string$MerchantId
     * @param string $Status
     * @param int $Score
     * @param string $DenialReason
     * @return bool|mixed
     */
    public function save($MerchantId, $Status, $Score, $DenialReason = null)
    {
        $this->getLogger()->info("Subordinate Approval Notification: 
            - MerchantId {$MerchantId} 
            - Status {$Status} 
            - Score {$Score}
            - DenialReason {$DenialReason}"
        );

        return $this->getSubordinateApprovalStatusChangedCommand()->execute($MerchantId, $Status, $Score, $DenialReason);
    }
}
