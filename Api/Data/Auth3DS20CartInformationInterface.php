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
 * Interface Auth3DS20CartInformationInterface
 *
 * @package Webjump\BraspagPagador\Api\Data
 */
interface Auth3DS20CartInformationInterface
{
    const BPMPI_CART_DESCRIPTION= 'bpmpi_cart_description';

    const BPMPI_CART_NAME = 'bpmpi_cart_name';

    const BPMPI_CART_SKU = 'bpmpi_cart_sku';

    const BPMPI_CART_QUANTITY = 'bpmpi_cart_quantity';

    const BPMPI_CART_UNITPRICE = 'bpmpi_cart_unitprice';

    /**
     * @return string
     */
    public function getBpmpiCartDescription(): string;

    /**
     * @param string $bpmpiCartDescription
     * @return void
     */
    public function setBpmpiCartDescription(string $bpmpiCartDescription): void;

    /**
     * @return string
     */
    public function getBpmpiCartName(): string;

    /**
     * @param string $bpmpiCartName
     * @return void
     */
    public function setBpmpiCartName(string $bpmpiCartName): void;

    /**
     * @return string
     */
    public function getBpmpiCartSku(): string;

    /**
     * @param string $bpmpiCartSku
     * @return void
     */
    public function setBpmpiCartSku(string $bpmpiCartSku): void;

    /**
     * @return int
     */
    public function getBpmpiCartQuantity(): int;

    /**
     * @param int $bpmpiCartQuantity
     * @return void
     */
    public function setBpmpiCartQuantity(int $bpmpiCartQuantity ): void;

    /**
     * @return float
     */
    public function getBpmpiCartUnitprice(): float;

    /**
     * @param float $bpmpiCartUnitprice
     * @return void
     */
    public function setBpmpiCartUnitprice(float $bpmpiCartUnitprice): void;
}
