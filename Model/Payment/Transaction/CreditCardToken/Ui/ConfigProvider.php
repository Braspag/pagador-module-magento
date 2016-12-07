<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCardToken\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface as InstallmentsBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens\BuilderInterface as TokensBuilder;
use Webjump\BraspagPagador\Api\Data\CardTokenInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;

/**
 * Braspag Transaction CreditCard Token
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcardtoken';

    protected $installments = [];

    protected $installmentsBuilder;

    protected $installmentsConfig;

    protected $tokensBuilder;

    protected $tokens = [];

    public function __construct(
        InstallmentsBuilder $installmentsBuilder,
        TokensBuilder $tokensBuilder,
        InstallmentsConfigInterface $installmentsConfig
    ) {
        $this->setInstallmentsBuilder($installmentsBuilder);
        $this->setTokensBuilder($tokensBuilder);
        $this->setInstallmentsConfig($installmentsConfig);
    }

    public function getConfig()
    {
        return [
            'payment' => [
                'ccform' => [
                    'installments' => [
                        'active' => [self::CODE => $this->getInstallmentsConfig()->isInterestByIssuer()],
                        'list' => $this->getInstallments(),
                    ],
                    'tokens' => [
                        'list' => $this->getTokens(),
                    ],
                ]
            ]
        ];
    }

    protected function getInstallments()
    {
        foreach ($this->getInstallmentsBuilder()->build() as $installment) {
            $this->installments[self::CODE][$installment->getId()] = $installment->getLabel();
        }

    	return $this->installments;
    }

    protected function getTokens()
    {
        foreach ($this->getTokensBuilder()->build() as $token) {
            $this->tokens[self::CODE][$token->getToken()] = $token->getAlias();
        }

        return $this->tokens;
    }

    protected function getInstallmentsBuilder()
    {
        return $this->installmentsBuilder;
    }

    protected function setInstallmentsBuilder(InstallmentsBuilder $installmentsBuilder)
    {
        $this->installmentsBuilder = $installmentsBuilder;

        return $this;
    }

    protected function getTokensBuilder()
    {
        return $this->tokensBuilder;
    }

    protected function setTokensBuilder(TokensBuilder $tokensBuilder)
    {
        $this->tokensBuilder = $tokensBuilder;

        return $this;
    }

    public function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }
}
