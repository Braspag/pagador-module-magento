<?php
namespace Page;

class ProductView extends DefaultPage
{
    public static $URL = 'fusion-backpack.html';
    public static $addToCartButton = '//*[@id="product-addtocart-button"]';
    public static $productAddedWithSuccessMessage = 'You added Fusion Backpack to your shopping cart.';

    public function addProductToCart()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->click(self::$addToCartButton);
        $this->user->waitForText(self::$productAddedWithSuccessMessage, 30);

        return $this;
    }
}
