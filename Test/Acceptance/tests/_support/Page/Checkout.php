<?php

namespace Page;

class Checkout extends DefaultPage
{

    public static $URL = 'checkout';
    public static $nextStepButton = '//*[@id="shipping-method-buttons-container"]/div/button';
    public static $checkoutBilletPayment = '//*[@value="braspag_pagador_billet"]';
    public static $placeOrderBilletButton = '//*[@id="checkout-payment-method-load"]/div/div[2]/div[2]/div[5]/div/button';

    public function setShipping()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->waitForElementVisible(self::$nextStepButton, 30);
        $this->user->click(self::$nextStepButton);

        return $this;
    }

    public function payWithBillet()
    {
        $this->user->waitForElementVisible(self::$checkoutBilletPayment, 60);
        $this->user->click(self::$checkoutBilletPayment);
        $this->user->click(self::$placeOrderBilletButton);

        return $this;
    }

    public function seeSuccessPage()
    {
        $this->user->waitFortext('Your order number is:', 30);
        $this->user->waitFortext('Print Billet', 30);

        return $this;
    }
}
