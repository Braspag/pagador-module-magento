<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi\Data;

use Braspag\BraspagPagador\Api\Data\MpiV3SessionInterface;
use Magento\Framework\DataObject;

/**
 * Concrete DTO. Stores the 3 fields the frontend needs after the INIT call.
 */
class MpiV3Session extends DataObject implements MpiV3SessionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getReferenceId()
    {
        return (string) $this->getData(self::REFERENCE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setReferenceId($referenceId)
    {
        return $this->setData(self::REFERENCE_ID, (string) $referenceId);
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return (string) $this->getData(self::TOKEN);
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        return $this->setData(self::TOKEN, (string) $token);
    }

    /**
     * {@inheritdoc}
     */
    public function getReservedOrderId()
    {
        return (string) $this->getData(self::RESERVED_ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setReservedOrderId($reservedOrderId)
    {
        return $this->setData(self::RESERVED_ORDER_ID, (string) $reservedOrderId);
    }
}
