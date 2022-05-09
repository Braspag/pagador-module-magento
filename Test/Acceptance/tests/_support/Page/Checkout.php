<?php

namespace Page;

class Checkout extends DefaultPage
{

    public static $URL = 'checkout';
    public static $nextStepButton = '//*[@id="shipping-method-buttons-container"]/div/button';
    public static $checkoutBoletoPayment = '//*[@value="braspag_pagador_boleto"]';
    public static $checkoutcreditCardPayment = '//*[@value="braspag_pagador_creditcard"]';
    public static $placeOrderBoletoButton = '//*[@id="checkout-payment-method-load"]/div/div[2]/div[2]/div[5]/div/button';
    public static $placeOrderCreditCardButton = '//*[@id="checkout-payment-method-load"]/div/div[3]/div[2]/div[3]/div/button';


    public static $selectCreditCardType = '//*[@name="payment[cc_type]"]';
    public static $selectCreditCardNumber = '//*[@name="payment[cc_number]"]';
    public static $selectCreditCardOwner = '//*[@name="payment[cc_owner]"]';
    public static $selectCreditCardExpMonth = '//*[@name="payment[cc_exp_month]"]';
    public static $selectCreditCardExpYear = '//*[@name="payment[cc_exp_year]"]';
    public static $selectCreditCardCid = '//*[@name="payment[cc_cid]"]';
    public static $selectCreditCardInstallments = '//*[@name="payment[cc_installments]"]';

    public function setShipping()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->waitForElementVisible(self::$nextStepButton, 30);
        $this->user->click(self::$nextStepButton);

        return $this;
    }

    public function payWithBoleto()
    {
        $this->user->waitForElementVisible(self::$checkoutBoletoPayment, 60);
        $this->user->click(self::$checkoutBoletoPayment);
        $this->user->click(self::$placeOrderBoletoButton);

        return $this;
    }

    public function payWithCreditCard()
    {
        $this->user->waitForElementVisible(self::$checkoutcreditCardPayment, 60);
        $this->user->click(self::$checkoutcreditCardPayment);

        $this->user->selectOption(self::$selectCreditCardType, 'Simulado*');
        $this->user->fillField(self::$selectCreditCardNumber, '0000.0000.0000.0001');
        $this->user->fillField(self::$selectCreditCardOwner, 'John Due');
        $this->user->selectOption(self::$selectCreditCardExpMonth, '02 - fevereiro');
        $this->user->selectOption(self::$selectCreditCardExpYear, '2019');
        $this->user->fillField(self::$selectCreditCardCid, '999');
        $option = $this->user->grabTextFrom(self::$selectCreditCardInstallments . '/option[3]');
        $this->user->selectOption(self::$selectCreditCardInstallments, $option);

        $this->user->click(self::$placeOrderCreditCardButton);
    }

    public function seeSuccessPage()
    {
        $this->user->waitFortext('Your order number is:', 30);

        return $this;
    }

    public function seeSuccessPageWithPrintBoletoLink()
    {
        $this->user->waitFortext('Your order number is:', 30);
        $this->user->waitFortext('Print Boleto', 30);

        return $this;
    }
}
