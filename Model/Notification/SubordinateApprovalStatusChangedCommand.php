<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model\Notification;

use Webjump\BraspagPagador\Model\SubordinateApprovalManager;
use \Webjump\BraspagPagador\Api\NotificationManagerInterface;

/**
 * Class SubordinateApprovalStatusChangedCommand
 * @package Webjump\BraspagPagador\Model\Notification
 */
class SubordinateApprovalStatusChangedCommand
{
    /**
     * @var Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface
     **/
    protected $config;

    /**
     * @var SubordinateApprovalManager
     */
    protected $approvalManager;

    /**
     * @var \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand
     */
    protected $getSubordinateCommand;

    /**
     * SubordinateApprovalStatusChangedCommand constructor.
     * @param \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config
     * @param SubordinateApprovalManager $approvalManager
     * @param \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
     */
    public function __construct(
        \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        SubordinateApprovalManager $approvalManager,
        \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
    ){
        $this->setApprovalManager($approvalManager);
        $this->setConfig($config);
        $this->setGetSubordinateCommand($getSubordinateCommand);
    }

    /**
     * @return SubordinateApprovalManager
     */
    public function getApprovalManager()
    {
        return $this->approvalManager;
    }

    /**
     * @param SubordinateApprovalManager $approvalManager
     */
    public function setApprovalManager($approvalManager)
    {
        $this->approvalManager = $approvalManager;
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
     * @return Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand
     */
    public function getGetSubordinateCommand()
    {
        return $this->getSubordinateCommand;
    }

    /**
     * @param Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
     */
    public function setGetSubordinateCommand($getSubordinateCommand)
    {
        $this->getSubordinateCommand = $getSubordinateCommand;
    }

    /**
     * @param $merchantId
     * @param $status
     * @param null $score
     * @param null $denialReason
     * @return mixed
     */
    public function execute($merchantId, $status, $score = null, $denialReason = null)
    {
        try {
            $this->getGetSubordinateCommand()->execute([
                'subordinate' => null,
                'merchantId' => $merchantId
            ]);

        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
