<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi\Data;

use Braspag\BraspagPagador\Api\Data\MpiV3EnrollResultInterface;
use Magento\Framework\DataObject;

class MpiV3EnrollResult extends DataObject implements MpiV3EnrollResultInterface
{
    public function getStatus()
    {
        return (int) $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, (int) $status);
    }

    public function getReasonCode()
    {
        return (string) $this->getData(self::REASON_CODE);
    }

    public function setReasonCode($reasonCode)
    {
        return $this->setData(self::REASON_CODE, (string) $reasonCode);
    }

    public function getReasonMessage()
    {
        return (string) $this->getData(self::REASON_MESSAGE);
    }

    public function setReasonMessage($reasonMessage)
    {
        return $this->setData(self::REASON_MESSAGE, (string) $reasonMessage);
    }

    public function getChallenge()
    {
        return $this->getData(self::CHALLENGE);
    }

    public function setChallenge($challenge)
    {
        return $this->setData(self::CHALLENGE, $challenge);
    }

    public function getAuthentication()
    {
        return $this->getData(self::AUTHENTICATION);
    }

    public function setAuthentication($authentication)
    {
        return $this->setData(self::AUTHENTICATION, $authentication);
    }
}
