<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Webjump\BraspagPagador\Api\Auth3DS20GetCartInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20CartInformationInterfaceFactory;

/**
 * Class Auth3DS20GetCart
 *
 * @package Webjump\BraspagPagador\Model
 */
class GetCart implements Auth3DS20GetCartInterface
{
    /**
     * @var
     */
    private $cartInformationFactory;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * Auth3DS20GetCart constructor.
     * @param Auth3DS20CartInformationInterfaceFactory $cartInformationFactory
     * @param PriceHelper $priceHelper
     */
    public function __construct(
        Auth3DS20CartInformationInterfaceFactory $cartInformationFactory,
        PriceHelper $priceHelper
    ){
        $this->cartInformationFactory = $cartInformationFactory;
        $this->priceHelper = $priceHelper;
    }

    /**
     * @inheritDoc
     */
    public function getCartData(\Magento\Quote\Api\Data\CartInterface $quote): array
    {
        $result = [];
        foreach ($quote->getItems() as $item)
        {
            $auth3DS20CartInformation = $this->cartItemInformation(
                $item->getSku(),
                $item->getName(),
                $item->getPrice(),
                $item->getQty()

            );

            $result[] = $auth3DS20CartInformation;
        }

        return $result;
    }

    public function cartItemInformation($sku, $name, $price, $qty): Auth3DS20CartInformationInterface
    {
        /** @var $auth3DS20CartInformation Auth3DS20CartInformationInterface */
        $auth3DS20CartInformation = $this->cartInformationFactory->create();

        $auth3DS20CartInformation->setBpmpiCartSku($sku);
        $auth3DS20CartInformation->setBpmpiCartName($name);
        $auth3DS20CartInformation->setBpmpiCartUnitprice((float)$this->priceHelper->currency($price, false, false));
        $auth3DS20CartInformation->setBpmpiCartQuantity((int)$qty);

        return $auth3DS20CartInformation;
    }
}

