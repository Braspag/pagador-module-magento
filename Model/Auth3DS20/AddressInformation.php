<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Magento\Framework\Model\AbstractExtensibleModel;
use Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface;

/**
 * Class Auth3DS20AddressInformation
 *
 * @package Webjump\BraspagPagador\Model
 */
class AddressInformation extends AbstractExtensibleModel implements Auth3DS20AddressInformationInterface
{
    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoPhoneNumber(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_PHONENUMBER);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoPhoneNumber(string $bpmpiBilltoPhoneNumber): void
    {
        $this->setData(self::BPMPI_BILLTO_PHONENUMBER, $bpmpiBilltoPhoneNumber);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoCustomerid(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_CUSTOMERID);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoCustomerid(string $bpmpiBilltoCustomerid): void
    {
        $this->setData(self::BPMPI_BILLTO_CUSTOMERID, $bpmpiBilltoCustomerid);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoEmail(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoEmail(string $bpmpiBilltoEmail): void
    {
        $this->setData(self::BPMPI_BILLTO_EMAIL, $bpmpiBilltoEmail);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoStreet1(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_STREET1);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoStreet1(string $bpmpiBilltoStreet1): void
    {
        $this->setData(self::BPMPI_BILLTO_STREET1, $bpmpiBilltoStreet1);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoStreet2(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_STREET2);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoStreet2(string $bpmpiBilltoStreet2): void
    {
        $this->setData(self::BPMPI_BILLTO_STREET2, $bpmpiBilltoStreet2);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoCity(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_CITY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoCity(string $bpmpiBilltoCity): void
    {
        $this->setData(self::BPMPI_BILLTO_CITY, $bpmpiBilltoCity);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoState(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_STATE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoState(string $bpmpiBilltoState): void
    {
        $this->setData(self::BPMPI_BILLTO_STATE, $bpmpiBilltoState);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoZipcode(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_ZIPCODE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoZipcode(string $bpmpiBilltoZipcode): void
    {
        $this->setData(self::BPMPI_BILLTO_ZIPCODE, $bpmpiBilltoZipcode);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiBilltoCountry(): string
    {
        return (string)$this->getData(self::BPMPI_BILLTO_COUNTRY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiBilltoCountry(string $bpmpiBilltoCountry): void
    {
        $this->setData(self::BPMPI_BILLTO_COUNTRY, $bpmpiBilltoCountry);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoSameasbillto(): bool
    {
        return (bool)$this->getData(self::BPMPI_SHIPTO_SAMEASBILLTO);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoSameasbillto(bool $bpmpiShiptoSameasbillto): void
    {
        $this->setData(self::BPMPI_SHIPTO_SAMEASBILLTO, $bpmpiShiptoSameasbillto);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoAddressee(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_ADDRESSEE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoAddressee(string $bpmpiShiptoAddressee): void
    {
        $this->setData(self::BPMPI_SHIPTO_ADDRESSEE, $bpmpiShiptoAddressee);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoPhonenumber(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_PHONENUMBER);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoPhonenumber(string $bpmpiShiptoPhonenumber): void
    {
        $this->setData(self::BPMPI_SHIPTO_PHONENUMBER, $bpmpiShiptoPhonenumber);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoEmail(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoEmail(string $bpmpiShiptoEmail): void
    {
        $this->setData(self::BPMPI_SHIPTO_EMAIL, $bpmpiShiptoEmail);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoStreet1(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_STREET1);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoStreet1(string $bpmpiShiptoStreet1): void
    {
        $this->setData(self::BPMPI_SHIPTO_STREET1, $bpmpiShiptoStreet1);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoStreet2(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_STREET2);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoStreet2(string $bpmpiShiptoStreet2): void
    {
        $this->setData(self::BPMPI_SHIPTO_STREET2, $bpmpiShiptoStreet2);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoCity(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_CITY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoCity(string $bpmpiShiptoCity): void
    {
        $this->setData(self::BPMPI_SHIPTO_CITY, $bpmpiShiptoCity);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoState(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_STATE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoState(string $bpmpiShiptoState): void
    {
        $this->setData(self::BPMPI_SHIPTO_STATE, $bpmpiShiptoState);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoZipcode(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_ZIPCODE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoZipcode(string $bpmpiShiptoZipcode): void
    {
        $this->setData(self::BPMPI_SHIPTO_ZIPCODE, $bpmpiShiptoZipcode);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiShiptoCountry(): string
    {
        return (string)$this->getData(self::BPMPI_SHIPTO_COUNTRY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiShiptoCountry(string $bpmpiShiptoCountry): void
    {
        $this->setData(self::BPMPI_SHIPTO_COUNTRY, $bpmpiShiptoCountry);
    }

}
