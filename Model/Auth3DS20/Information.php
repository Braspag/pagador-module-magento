<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);
namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Webjump\BraspagPagador\Api\Data\Auth3DS20InformationInterface;
use Webjump\BraspagPagador\Api\Auth3DS20GetAddressInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Webjump\BraspagPagador\Api\Auth3DS20GetCartInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface;
use Webjump\BraspagPagador\Api\Auth3DS20UserAccountInterface;

/**
 * Class Auth3DS20Information
 *
 * @package Webjump\BraspagPagador\Model
 */
class Information extends AbstractExtensibleModel implements Auth3DS20InformationInterface
{
    /**
     * @var Auth3DS20GetAddressInterface
     */
    private $auth3DS20AddressInformation;

    /**
     * @var Auth3DS20GetCartInterface
     */
    private $auth3DS20CartInformation;

    /**
     * @var Auth3DS20CartInformationInterface
     */
    private $auth3DS20CartInformationInterface;

    /**
     * @var Auth3DS20UserAccountInterface
     */
    private $auth3DS20UserAccountInformation;


    /**
     * Auth3DS20Information constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param Auth3DS20GetAddressInterface $auth3DS20AddressInformation
     * @param Auth3DS20GetCartInterface $auth3DS20CartInformation
     * @param Auth3DS20CartInformationInterface $auth3DS20CartInformationInterface
     * @param Auth3DS20UserAccountInterface $auth3DS20UserAccountInformation
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [],
        Auth3DS20GetAddressInterface $auth3DS20AddressInformation,
        Auth3DS20GetCartInterface $auth3DS20CartInformation,
        Auth3DS20CartInformationInterface $auth3DS20CartInformationInterface,
        Auth3DS20UserAccountInterface $auth3DS20UserAccountInformation

    ){
        $this->auth3DS20AddressInformation = $auth3DS20AddressInformation;
        $this->auth3DS20CartInformation = $auth3DS20CartInformation;
        $this->auth3DS20CartInformationInterface = $auth3DS20CartInformationInterface;
        $this->auth3DS20UserAccountInformation = $auth3DS20UserAccountInformation;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data);
    }


    /**
     * @inheritDoc
     */
    public function getBpmpiDebitAuth(): bool
    {
        return (bool)$this->getData(self::BPMPI_DEBIT_AUTH);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiDebitAuth(bool $bpmpiDebitAuth): void
    {
        $this->setData(self::BPMPI_DEBIT_AUTH, $bpmpiDebitAuth);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCreditAuth(): bool
    {
        return (bool)$this->getData(self::BPMPI_CREDIT_AUTH);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCreditAuth(bool $bpmpiCreditAuth): void
    {
        $this->setData(self::BPMPI_CREDIT_AUTH, $bpmpiCreditAuth);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiDebitAuthNotifyonly(): bool
    {
        return (bool)$this->getData(self::BPMPI_DEBIT_AUTH_NOTIFYONLY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiDebitAuthNotifyonly(bool $bpmpiAuthDebitNotifyonly): void
    {
        $this->setData(self::BPMPI_DEBIT_AUTH_NOTIFYONLY, $bpmpiAuthDebitNotifyonly);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCreditAuthNotifyonly(): bool
    {
        return (bool)$this->getData(self::BPMPI_CREDIT_AUTH_NOTIFYONLY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCreditAuthNotifyonly(bool $bpmpiAuthCreditNotifyonly): void
    {
        $this->setData(self::BPMPI_CREDIT_AUTH_NOTIFYONLY, $bpmpiAuthCreditNotifyonly);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiTotalamount(): float
    {
        return (float)$this->getData(self::BPMPI_TOTALAMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiTotalamount(float $bpmpiTotalAmount): void
    {
        $this->setData(self::BPMPI_TOTALAMOUNT, $bpmpiTotalAmount);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCurrency(): string
    {
        return (string)$this->getData(self::BPMPI_CURRENCY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCurrency(string $bpmpiCurrency): void
    {
        $this->setData(self::BPMPI_CURRENCY, $bpmpiCurrency);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiOrderNumber(): string
    {
        return (string)$this->getData(self::BPMPI_ORDERNUMBER);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiOrderNumber(string $bpmpiOrderNumber): void
    {
        $this->setData(self::BPMPI_ORDERNUMBER, $bpmpiOrderNumber);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiTransactionMode(): string
    {
        return (string)$this->getData(self::BPMPI_TRANSACTION_MODE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiTransactionMode(string $bpmpiTransactionMode): void
    {
        $this->setData(self::BPMPI_TRANSACTION_MODE, $bpmpiTransactionMode);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiMerchantUrl(): string
    {
        return (string)$this->getData(self::BPMPI_MERCHANT_URL);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiMerchantUrl(string $bpmpiTransactionMode): void
    {
        $this->setData(self::BPMPI_MERCHANT_URL, $bpmpiTransactionMode);
    }

    /**
     * @inheritDoc
     */
    public function getAddressData(): \Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface
    {
        return $this->getData(self::BPMPI_ADDRESSES_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setAddressesData(\Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface $addressesData): void
    {
        $this->setData(self::BPMPI_ADDRESSES_DATA, $addressesData);
    }

    /**
     * @inheritDoc
     */
    public function getCartData(): array
    {
        return $this->getData(self::BPMPI_CART_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setCartData(array $cartData): void
    {
        $this->setData(self::BPMPI_CART_DATA, $cartData);
    }

    /**
     * @inheritDoc
     */
    public function getUserAccount(): \Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface
    {
        return $this->getData(self::BPMPI_USER_ACCOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setUserAccount(\Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface $userAccount): void
    {
        $this->setData(self::BPMPI_USER_ACCOUNT, $userAccount);
    }
}
