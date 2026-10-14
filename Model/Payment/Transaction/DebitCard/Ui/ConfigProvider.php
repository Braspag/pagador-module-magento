<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface as MpiV3Config;
use Braspag\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface;

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

    protected $debitConfig;

    /**
     * @var MpiV3Config
     */
    private $mpiV3Config;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ConfigInterface $debitConfig,
        MpiV3Config $mpiV3Config,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->setDebitConfig($debitConfig);
        $this->mpiV3Config = $mpiV3Config;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * MPI V3 and Silent Order Post are mutually exclusive per Cielo/Braspag
     * (Ana Beatriz, 2026-09-28). See CreditCard ConfigProvider for details.
     *
     * @return bool
     */
    private function isMpiEffectivelyActive()
    {
        if (!$this->getDebitConfig()->isAuth3Ds20Active()) {
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
                'dcform' => [
                    'superdebito' => [
                        'active' => [self::CODE => $this->getDebitConfig()->isSuperDebitoActive()]
                    ],
                    'bpmpi_authentication' => [
                        // If SOP is active for this method, MPI V3 is force-off
                        // (see isMpiEffectivelyActive docblock).
                        'active' => $this->isMpiEffectivelyActive(),
                        'sandbox' => $this->mpiV3Config->isSandbox(),
                        'debug' => $this->mpiV3Config->isDebugActive(),
                        'mastercard_notify_only' => $this->getDebitConfig()->isAuth3Ds20MCOnlyNotifyActive(),
                        'authorize_on_error' => $this->getDebitConfig()->isAuth3Ds20AuthorizedOnError(),
                        'authorize_on_failure' => $this->getDebitConfig()->isAuth3Ds20AuthorizedOnFailure(),
                        'authorize_on_unenrolled' => $this->getDebitConfig()->isAuth3Ds20AuthorizeOnUnenrolled(),
                        'mdd1' => $this->getDebitConfig()->getAuth3Ds20Mdd1(),
                        'mdd2' => $this->getDebitConfig()->getAuth3Ds20Mdd2(),
                        'mdd3' => $this->getDebitConfig()->getAuth3Ds20Mdd3(),
                        'mdd4' => $this->getDebitConfig()->getAuth3Ds20Mdd4(),
                        'mdd5' => $this->getDebitConfig()->getAuth3Ds20Mdd5()
                    ],
                    'card_view' => [
                        'active' => $this->getDebitConfig()->isCardViewActive()
                    ]
                ],
                'redirect_after_place_order' => $this->getDebitConfig()->getRedirectAfterPlaceOrder()
            ]
        ];

        return $config;
    }

    protected function getDebitConfig()
    {
        return $this->debitConfig;
    }

    protected function setDebitConfig($debitConfig)
    {
        $this->debitConfig = $debitConfig;

        return $this;
    }
}