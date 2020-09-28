<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Api\Data;

/**
 * Interface Auth3DS20UserAccountInformationInterface
 *
 * @package Webjump\BraspagPagador\Api\Data
 */
interface Auth3DS20UserAccountInformationInterface
{
    const BPMPI_USERACCOUNT_GUEST = 'bpmpi_useraccount_guest';

    const BPMPI_USERACCOUNT_CREATEDDATE = 'bpmpi_useraccount_createddate';

    const BPMPI_USERACCOUNT_CHANGEDDATE = 'bpmpi_useraccount_changeddate';

    const BPMPI_USERACCOUNT_AUTHENTICATIONMETHOD = 'bpmpi_useraccount_authenticationmethod';

    const BPMPI_USERACCOUNT_AUTHENTICATIONPROTOCOL = 'bpmpi_useraccount_authenticationprotocol';

    const BPMPI_DEVICE_IPADDRESS = 'bpmpi_device_ipaddress';

    /**
     * @return bool
     */
    public function getBpmpiUseraccountGuest(): bool;

    /**
     * @param bool $bpmpiUseraccountGuest
     * @return void
     */
    public function setBpmpiUseraccountGuest(bool $bpmpiUseraccountGuest): void;

    /**
     * @return string
     */
    public function getBpmpiUseraccountCreateddate(): string;

    /**
     * @param string $bpmpiUseraccountCreateddate
     * @return void
     */
    public function setBpmpiUseraccountCreateddate(string $bpmpiUseraccountCreateddate): void;

    /**
     * @return string
     */
    public function getBpmpiUseraccountChangeddate(): string;

    /**
     * @param string $bpmpiUseraccountChangeddate
     * @return void
     */
    public function setBpmpiUseraccountChangeddate(string $bpmpiUseraccountChangeddate): void;

    /**
     * @return int
     */
    public function getBpmpiUseraccountAuthenticationmethod(): int;

    /**
     * @param int $bpmpiUseraccountAuthenticationmethod
     * @return void
     */
    public function setBpmpiUseraccountAuthenticationmethod(int $bpmpiUseraccountAuthenticationmethod): void;

    /**
     * @return string
     */
    public function getBpmpiUseraccountAuthenticationprotocol(): string;

    /**
     * @param string $bpmpiUseraccountAuthenticationprotocol
     * @return void
     */
    public function setBpmpiUseraccountAuthenticationprotocol(string $bpmpiUseraccountAuthenticationprotocol): void;

    /**
     * @return string
     */
    public function getBpmpiDeviceIpaddress(): string;

    /**
     * @param string $bpmpiDeviceIpaddress
     * @return void
     */
    public function setBpmpiDeviceIpaddress(string $bpmpiDeviceIpaddress): void;
}
