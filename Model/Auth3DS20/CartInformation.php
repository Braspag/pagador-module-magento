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
use Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface;

/**
 * Class Auth3DS20CartInformation
 *
 * @package Webjump\BraspagPagador\Model
 */
class CartInformation extends AbstractExtensibleModel implements Auth3DS20CartInformationInterface
{

    /**
     * @inheritDoc
     */
    public function getBpmpiCartDescription(): string
    {
        return (string)$this->getData(self::BPMPI_CART_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCartDescription(string $bpmpiCartDescription): void
    {
        $this->setData(self::BPMPI_CART_DESCRIPTION, $bpmpiCartDescription);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCartName(): string
    {
        return (string)$this->getData(self::BPMPI_CART_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCartName(string $bpmpiCartName): void
    {
        $this->setData(self::BPMPI_CART_NAME, $bpmpiCartName);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCartSku(): string
    {
        return (string)$this->getData(self::BPMPI_CART_SKU);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCartSku(string $bpmpiCartSku): void
    {
        $this->setData(self::BPMPI_CART_SKU, $bpmpiCartSku);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCartQuantity(): int
    {
        return (int)$this->getData(self::BPMPI_CART_QUANTITY);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCartQuantity(int $bpmpiCartQuantity): void
    {
        $this->setData(self::BPMPI_CART_QUANTITY, $bpmpiCartQuantity);
    }

    /**
     * @inheritDoc
     */
    public function getBpmpiCartUnitprice(): float
    {
        return (float)$this->getData(self::BPMPI_CART_UNITPRICE);
    }

    /**
     * @inheritDoc
     */
    public function setBpmpiCartUnitprice(float $bpmpiCartUnitprice): void
    {
        $this->setData(self::BPMPI_CART_UNITPRICE, $bpmpiCartUnitprice);
    }
}
