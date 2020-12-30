<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Auth3DS20UserAccountInformation
 *
 * @package Webjump\BraspagPagador\Model
 */
class UserAccountInformation extends AbstractExtensibleModel implements Auth3DS20UserAccountInformationInterface
{

    /**
     * @inheritDoc
     */
    public function getBpmpiUseraccountGuest(): bool
    {
        return (bool)$this->getData(self::BPMPI_USERACCOUNT_GUEST);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiUseraccountGuest(bool $bpmpiUseraccountGuest): void
    {
        $this->setData(self::BPMPI_USERACCOUNT_GUEST, $bpmpiUseraccountGuest);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiUseraccountCreateddate(): string
    {
        return (string)$this->getData(self::BPMPI_USERACCOUNT_CREATEDDATE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiUseraccountCreateddate(string $bpmpiUseraccountCreateddate): void
    {
        $this->setData(self::BPMPI_USERACCOUNT_CREATEDDATE, $bpmpiUseraccountCreateddate);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiUseraccountChangeddate(): string
    {
        return (string)$this->getData(self::BPMPI_USERACCOUNT_CHANGEDDATE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiUseraccountChangeddate(string $bpmpiUseraccountChangeddate): void
    {
        $this->setData(self::BPMPI_USERACCOUNT_CHANGEDDATE, $bpmpiUseraccountChangeddate);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiUseraccountAuthenticationMethod(): int
    {
        return (int)$this->getData(self::BPMPI_USERACCOUNT_AUTHENTICATIONMETHOD);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiUseraccountAuthenticationMethod(int $bpmpiUseraccountAuthenticationmethod): void
    {
        $this->setData(self::BPMPI_USERACCOUNT_AUTHENTICATIONMETHOD, $bpmpiUseraccountAuthenticationmethod);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiUseraccountAuthenticationprotocol(): string
    {
        return (string)$this->getData(self::BPMPI_USERACCOUNT_AUTHENTICATIONPROTOCOL);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiUseraccountAuthenticationprotocol(string $bpmpiUseraccountAuthenticationprotocol): void
    {
        $this->setData(self::BPMPI_USERACCOUNT_AUTHENTICATIONPROTOCOL, $bpmpiUseraccountAuthenticationprotocol);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiDeviceIpaddress(): string
    {
        return (string)$this->getData(self::BPMPI_DEVICE_IPADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiDeviceIpaddress(string $bpmpiDeviceIpaddress): void
    {
        $this->setData(self::BPMPI_DEVICE_IPADDRESS, $bpmpiDeviceIpaddress);
    }
}
