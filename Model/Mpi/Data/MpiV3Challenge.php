<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi\Data;

use Braspag\BraspagPagador\Api\Data\MpiV3ChallengeInterface;
use Magento\Framework\DataObject;

class MpiV3Challenge extends DataObject implements MpiV3ChallengeInterface
{
    public function getAcsUrl()
    {
        return (string) $this->getData(self::ACS_URL);
    }

    public function setAcsUrl($acsUrl)
    {
        return $this->setData(self::ACS_URL, (string) $acsUrl);
    }

    public function getPareq()
    {
        return (string) $this->getData(self::PAREQ);
    }

    public function setPareq($pareq)
    {
        return $this->setData(self::PAREQ, (string) $pareq);
    }

    public function getTransactionId()
    {
        return (string) $this->getData(self::TRANSACTION_ID);
    }

    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, (string) $transactionId);
    }
}
