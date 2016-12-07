<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface  as InstallmentsBuilder;
use Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui\AbstractInstallmentsConfigProvider;

/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class InstallmentsConfigProvider extends AbstractInstallmentsConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';
}
