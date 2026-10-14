<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface as MpiV3Config;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfig;

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
    public const CODE = 'braspag_pagador_creditcard';

    protected $creditCardConfig;

    /**
     * @var MpiV3Config
     */
    private $mpiV3Config;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        CreditCardConfig $creditCardConfig,
        MpiV3Config $mpiV3Config,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->setCreditCardConfig($creditCardConfig);
        $this->mpiV3Config = $mpiV3Config;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * MPI V3 and Silent Order Post cannot be enabled at the same time —
     * confirmed by Cielo/Braspag (Ana Beatriz, 2026-09-28). SOP replaces the
     * card number input with an iframe hosted at Braspag, so the MPI V3
     * frontend cannot read the PAN from the DOM to send to ENROLL. When both
     * toggles are on in admin, we silently disable MPI V3 for this method
     * and honor the SOP flow.
     *
     * @return bool
     */
    private function isMpiEffectivelyActive()
    {
        if (!$this->getCreditCardConfig()->isAuth3Ds20Active()) {
            return false;
        }
        $sopActive = (bool) $this->scopeConfig->getValue(
            'payment/' . self::CODE . '/silentorderpost_active',
            ScopeInterface::SCOPE_STORE
        );
        return !$sopActive;
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'savecard' => [
                        'active' => [self::CODE => $this->getCreditCardConfig()->isSaveCardActive()]
                    ],
                    'tow_card' => [
                        'active' => [self::CODE => boolval($this->getCreditCardConfig()->isTwoCardActive())]
                    ],
                    'bpmpi_authentication' => [
                        // If SOP is active for this method, MPI V3 is force-off
                        // (see isMpiEffectivelyActive docblock).
                        'active' => $this->isMpiEffectivelyActive(),
                        'sandbox' => $this->mpiV3Config->isSandbox(),
                        'debug' => $this->mpiV3Config->isDebugActive(),
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
                    ],
                    self::CODE => [
                        'installments_rules' => [
                           'active' =>  boolval($this->getCreditCardConfig()->isInstallmentsRulesActive()),
                           'rules'  =>  $this->getCreditCardConfig()->getInstallmentsCardsRules(),
                        ],
                        'cc_types' => $this->getCreditCardConfig()->getCcTypes(),
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