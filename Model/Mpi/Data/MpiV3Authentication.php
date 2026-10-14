<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi\Data;

use Braspag\BraspagPagador\Api\Data\MpiV3AuthenticationInterface;
use Magento\Framework\DataObject;

class MpiV3Authentication extends DataObject implements MpiV3AuthenticationInterface
{
    public function getCavv()
    {
        return (string) $this->getData(self::CAVV);
    }

    public function setCavv($cavv)
    {
        return $this->setData(self::CAVV, (string) $cavv);
    }

    public function getXid()
    {
        return (string) $this->getData(self::XID);
    }

    public function setXid($xid)
    {
        return $this->setData(self::XID, (string) $xid);
    }

    public function getEci()
    {
        return (string) $this->getData(self::ECI);
    }

    public function setEci($eci)
    {
        return $this->setData(self::ECI, (string) $eci);
    }

    public function getVersion()
    {
        return (string) $this->getData(self::VERSION);
    }

    public function setVersion($version)
    {
        return $this->setData(self::VERSION, (string) $version);
    }

    public function getDirectoryServerTransactionId()
    {
        return (string) $this->getData(self::DIRECTORY_SERVER_TRANSACTION_ID);
    }

    public function setDirectoryServerTransactionId($directoryServerTransactionId)
    {
        return $this->setData(self::DIRECTORY_SERVER_TRANSACTION_ID, (string) $directoryServerTransactionId);
    }
}
