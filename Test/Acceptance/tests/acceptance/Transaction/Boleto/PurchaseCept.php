<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Purchase a produtct with boleto');

Page\CustomerLogin::of($I)->doLogin();
Page\ProductView::of($I)->addProductToCart();
Page\Checkout::of($I)->setShipping();
Page\Checkout::of($I)->payWithBoleto();
Page\Checkout::of($I)->seeSuccessPageWithPrintBoletoLink();

