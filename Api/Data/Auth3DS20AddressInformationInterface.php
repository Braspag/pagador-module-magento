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
 * Interface Auth3DS20AddressInformationInterface
 *
 * @package Webjump\BraspagPagador\Api\Data
 */
Interface Auth3DS20AddressInformationInterface
{
    const BPMPI_BILLTO_PHONENUMBER = 'bpmpi_billto_phonenumber';

    const BPMPI_BILLTO_CUSTOMERID = 'bpmpi_billto_customerid';

    const BPMPI_BILLTO_EMAIL = 'bpmpi_billto_email';

    const BPMPI_BILLTO_STREET1 = 'bpmpi_billto_street1';

    const BPMPI_BILLTO_STREET2 = 'bpmpi_billto_street2';

    const BPMPI_BILLTO_CITY = 'bpmpi_billto_city';

    const BPMPI_BILLTO_STATE= 'bpmpi_billto_state';

    const BPMPI_BILLTO_ZIPCODE = 'bpmpi_billto_zipcode';

    const BPMPI_BILLTO_COUNTRY = 'bpmpi_billto_country';

    const BPMPI_SHIPTO_SAMEASBILLTO = 'bpmpi_shipto_sameasbillto';

    const BPMPI_SHIPTO_ADDRESSEE = 'bpmpi_shipto_addressee';

    const BPMPI_SHIPTO_PHONENUMBER = 'bpmpi_shipto_phonenumber';

    const BPMPI_SHIPTO_EMAIL = 'bpmpi_shipto_email';

    const BPMPI_SHIPTO_STREET1 = 'bpmpi_shipto_street1';

    const BPMPI_SHIPTO_STREET2 = 'bpmpi_shipto_street2';

    const BPMPI_SHIPTO_CITY = 'bpmpi_shipto_city';

    const BPMPI_SHIPTO_STATE = 'bpmpi_shipto_state';

    const BPMPI_SHIPTO_ZIPCODE = 'bpmpi_shipto_zipcode';

    const BPMPI_SHIPTO_COUNTRY = 'bpmpi_shipto_country';

    /**
     * @return string
     */
    public function getBpmpiBilltoPhoneNumber(): string;

    /**
     * @param string $bpmpiBilltoPhoneNumber
     * @return void;
     */
    public function setBpmpiBilltoPhoneNumber(string $bpmpiBilltoPhoneNumber): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoCustomerid(): string;

    /**
     * @param string $bpmpiBilltoCustomerid
     * @return void;
     */
    public function setBpmpiBilltoCustomerid(string $bpmpiBilltoCustomerid ): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoEmail(): string;

    /**
     * @param string $bpmpiBilltoEmail
     * @return void;
     */
    public function setBpmpiBilltoEmail(string $bpmpiBilltoEmail): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoStreet1(): string;

    /**
     * @param string $bpmpiBilltoStreet1
     * @return void;
     */
    public function setBpmpiBilltoStreet1(string $bpmpiBilltoStreet1): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoStreet2(): string;

    /**
     * @param string $bpmpiBilltoStreet2
     * @return void;
     */
    public function setBpmpiBilltoStreet2(string $bpmpiBilltoStreet2): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoCity(): string;

    /**
     * @param string $bpmpiBilltoCity
     * @return void;
     */
    public function setBpmpiBilltoCity(string $bpmpiBilltoCity): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoState(): string;

    /**
     * @param string $bpmpiBilltoState
     * @return void;
     */
    public function setBpmpiBilltoState(string $bpmpiBilltoState): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoZipcode(): string;

    /**
     * @param string $bpmpiBilltoZipcode
     * @return void;
     */
    public function setBpmpiBilltoZipcode(string $bpmpiBilltoZipcode): void;

    /**
     * @return string
     */
    public function getBpmpiBilltoCountry(): string;

    /**
     * @param string $bpmpiBilltoCountry
     * @return void;
     */
    public function setBpmpiBilltoCountry(string $bpmpiBilltoCountry): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoSameasbillto(): bool;

    /**
     * @param string $bpmpiShiptoSameasbillto
     * @return void;
     */
    public function setBpmpiShiptoSameasbillto(bool $bpmpiShiptoSameasbillto ): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoAddressee(): string;

    /**
     * @param string $bpmpiShiptoAddressee
     * @return void;
     */
    public function setBpmpiShiptoAddressee(string $bpmpiShiptoAddressee): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoPhonenumber(): string;

    /**
     * @param string $bpmpiShiptoPhonenumber
     * @return void;
     */
    public function setBpmpiShiptoPhonenumber(string $bpmpiShiptoPhonenumber ): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoEmail(): string;

    /**
     * @param string $bpmpiShiptoEmail
     * @return void;
     */
    public function setBpmpiShiptoEmail(string $bpmpiShiptoEmail): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoStreet1(): string;

    /**
     * @param string $bpmpiShiptoStreet1
     * @return void;
     */
    public function setBpmpiShiptoStreet1(string $bpmpiShiptoStreet1 ): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoStreet2(): string;

    /**
     * @param string $bpmpiShiptoStreet2
     * @return void;
     */
    public function setBpmpiShiptoStreet2(string $bpmpiShiptoStreet2 ): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoCity(): string;

    /**
     * @param string $bpmpiShiptoCity
     * @return void;
     */
    public function setBpmpiShiptoCity(string $bpmpiShiptoCity ): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoState(): string;

    /**
     * @param string $bpmpiShiptoState
     * @return void;
     */
    public function setBpmpiShiptoState(string $bpmpiShiptoState): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoZipcode(): string;

    /**
     * @param string $bpmpiShiptoZipcode
     * @return void;
     */
    public function setBpmpiShiptoZipcode(string $bpmpiShiptoZipcode): void;

    /**
     * @return string
     */
    public function getBpmpiShiptoCountry(): string;

    /**
     * @param string $bpmpiShiptoCountry
     * @return void;
     */
    public function setBpmpiShiptoCountry(string $bpmpiShiptoCountry): void;
}
