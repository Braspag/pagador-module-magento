<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Installments\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Installments\InstallmentInterface;

/**
 * Braspag Transaction DebitCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_debitcard';

    public function getConfig()
    {
        return [];
    }
}
