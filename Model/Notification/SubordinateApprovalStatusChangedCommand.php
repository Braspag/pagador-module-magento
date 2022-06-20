<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Braspag\BraspagPagador\Model\Notification;

use Braspag\BraspagPagador\Model\SubordinateApprovalManager;
use Braspag\BraspagPagador\Api\NotificationManagerInterface;

/**
 * Class SubordinateApprovalStatusChangedCommand
 * @package Braspag\BraspagPagador\Model\Notification
 */
class SubordinateApprovalStatusChangedCommand
{
    /**
     * @var Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface
     **/
    protected $config;

    /**
     * @var SubordinateApprovalManager
     */
    protected $approvalManager;

    /**
     * @var \Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand
     */
    protected $getSubordinateCommand;

    /**
     * SubordinateApprovalStatusChangedCommand constructor.
     * @param \Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config
     * @param SubordinateApprovalManager $approvalManager
     * @param \Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
     */
    public function __construct(
        \Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        SubordinateApprovalManager $approvalManager,
        \Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
    ) {
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
     * @return Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand
     */
    public function getGetSubordinateCommand()
    {
        return $this->getSubordinateCommand;
    }

    /**
     * @param Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\GetSubordinateCommand $getSubordinateCommand
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