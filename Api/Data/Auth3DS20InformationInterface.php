<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Api\Data;

use Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface;

/**
 * Interface Auth3DS20InformationInterface
 *
 * @package Webjump\BraspagPagador\Api\Data
 */
interface Auth3DS20InformationInterface
{
    const BPMPI_DEBIT_AUTH = 'bpmpi_debit_auth';

    const BPMPI_CREDIT_AUTH = 'bpmpi_credit_auth';

    const BPMPI_DEBIT_AUTH_NOTIFYONLY = 'bpmpi_debit_auth_notifyonly';

    const BPMPI_CREDIT_AUTH_NOTIFYONLY = 'bpmpi_credit_auth_notifyonly';

    const BPMPI_TOTALAMOUNT = 'bpmpi_totalamount';

    const BPMPI_CURRENCY = 'bpmpi_currency';

    const BPMPI_ORDERNUMBER = 'bpmpi_ordernumber';

    const BPMPI_TRANSACTION_MODE = 'bpmpi_transaction_mode';

    const BPMPI_MERCHANT_URL = 'bpmpi_merchant_url';

    const BPMPI_ADDRESSES_DATA = 'addresses_data';

    const BPMPI_CART_DATA = 'cart_data';

    const BPMPI_USER_ACCOUNT = 'user_account';

    /**
     * @return bool
     */
    public function getBpmpiDebitAuth(): bool;

    /**
     * @param bool $bpmpiDebitAuth
     * @return void;
     */
    public function setBpmpiDebitAuth(bool $bpmpiDebitAuth): void;

    /**
     * @return bool
     */
    public function getBpmpiCreditAuth(): bool;

    /**
     * @param bool $bpmpiCreditAuth
     * @return void
     */
    public function setBpmpiCreditAuth(bool $bpmpiCreditAuth): void;

    /**
     * @return bool
     */
    public function getBpmpiDebitAuthNotifyonly(): bool;

    /**
     * @param bool $bpmpiAuthDebitNotifyonly
     * @return void;
     */
    public function setBpmpiDebitAuthNotifyonly(bool $bpmpiAuthDebitNotifyonly): void;

    /**
     * @return bool
     */
    public function getBpmpiCreditAuthNotifyonly(): bool;

    /**
     * @param bool $bpmpiAuthCreditNotifyonly
     * @return void;
     */
    public function setBpmpiCreditAuthNotifyonly(bool $bpmpiAuthCreditNotifyonly): void;

    /**
     * @return float
     */
    public function getBpmpiTotalamount(): float;

    /**
     * @return float
     */
    public function setBpmpiTotalamount(float $bpmpiTotalAmount): void;

    /**
     * @return string
     */
    public function getBpmpiCurrency(): string;

    /**
     * @return string
     */
    public function setBpmpiCurrency(string $bpmpiCurrency): void;

    /**
     * @return string
     */
    public function getBpmpiOrdernumber(): string;

    /**
     * @return string
     */
    public function setBpmpiOrdernumber(string $bpmpiOrderNumber): void;

    /**
     * @return string
     */
    public function getBpmpiTransactionMode(): string;

    /**
     * @return string
     */
    public function setBpmpiTransactionMode(string $bpmpiTransactionMode): void;

    /**
     * @return string
     */
    public function getBpmpiMerchantUrl(): string;

    /**
     * @param string $bpmpiMerchantUrl
     * @return void
     */
    public function setBpmpiMerchantUrl(string $bpmpiMerchantUrl): void;

    /**
     * @return \Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface
     */
    public function getAddressData(): \Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface;

    /**
     * @param \Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface $addressesData
     * @return void
     */
    public function setAddressesData(\Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface $addressesData): void;

    /**
     * @return \Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface[]
     */
    public function getCartData(): array;

    /**
     * @param array $cartData
     * @return void
     */
    public function setCartData(array $cartData): void;

    /**
     * @return \Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface
     */
    public function getUserAccount(): \Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface;

    /**
     * @param \Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface $userAccount
     * @return void
     */
    public function setUserAccount(\Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface $userAccount): void;
}
