<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfig;

/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';

    protected $creditCardConfig;

    public function __construct(
        CreditCardConfig $creditCardConfig
    ) {
        $this->setCreditCardConfig($creditCardConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'savecard' => [
                        'active' => [self::CODE => $this->getCreditCardConfig()->isSaveCardActive()]
                    ],
                    'bpmpi_authentication' => [
                        'active' => $this->getCreditCardConfig()->isAuth3Ds20Active(),
                        'mastercard_notify_only' => $this->getCreditCardConfig()->isAuth3Ds20MCOnlyNotifyActive(),
                        'authorize_on_error' => $this->getCreditCardConfig()->isAuth3Ds20AuthorizedOnError(),
                        'authorize_on_failure' => $this->getCreditCardConfig()->isAuth3Ds20AuthorizedOnFailure(),
                        'authorize_on_unenrolled' => $this->getCreditCardConfig()->isAuth3Ds20AuthorizeOnUnenrolled(),
                        'mdd1' => $this->getCreditCardConfig()->getAuth3Ds20Mdd1(),
                        'mdd2' => $this->getCreditCardConfig()->getAuth3Ds20Mdd2(),
                        'mdd3' => $this->getCreditCardConfig()->getAuth3Ds20Mdd3(),
                        'mdd4' => $this->getCreditCardConfig()->getAuth3Ds20Mdd4(),
                        'mdd5' => $this->getCreditCardConfig()->getAuth3Ds20Mdd5()
                    ],
                    'card_view' => [
                        'active' => $this->getCreditCardConfig()->isCardViewActive()
                    ]
                ]
            ]
        ];

        return $config;
    }

    public function getCreditCardConfig()
    {
        return $this->creditCardConfig;
    }

    protected function setCreditCardConfig($creditCardConfig)
    {
        $this->creditCardConfig = $creditCardConfig;

        return $this;
    }
}
