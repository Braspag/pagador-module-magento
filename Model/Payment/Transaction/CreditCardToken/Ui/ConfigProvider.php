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
    /**
     *
     */
    const CODE = 'braspag_pagador_creditcardtoken';

    /**
     * @var array
     */
    protected $installments = [];

    /**
     * @var
     */
    protected $installmentsBuilder;

    /**
     * @var
     */
    protected $installmentsConfig;

    /**
     * @var
     */
    protected $tokensBuilder;

    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * ConfigProvider constructor.
     *
     * @param InstallmentsBuilder         $installmentsBuilder
     * @param TokensBuilder               $tokensBuilder
     * @param InstallmentsConfigInterface $installmentsConfig
     */
    public function __construct(
        InstallmentsBuilder $installmentsBuilder,
        TokensBuilder $tokensBuilder,
        InstallmentsConfigInterface $installmentsConfig
    )
    {
        $this->setInstallmentsBuilder($installmentsBuilder);
        $this->setTokensBuilder($tokensBuilder);
        $this->setInstallmentsConfig($installmentsConfig);
    }

    /**
     * Get data to use with saved card payment method
     *
     * @return array
     */
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
                        'list' => $this->getTokens()
                    ],
                    'cardtokensaved' => $this->getCardTokenSaved()
                ]
            ]
        ];
    }

    /**
     * Return installments for credit card
     *
     * @return array
     */
    protected function getInstallments()
    {
        foreach ($this->getInstallmentsBuilder()->build() as $installment) {
            $this->installments[self::CODE][$installment->getId()] = $installment->getLabel();
        }

        return $this->installments;
    }

    /**
     * @return array
     */
    protected function getTokens()
    {
        $this->tokens[self::CODE] = [];

        foreach ($this->getTokensBuilder()->build() as $token) {
            $this->tokens[self::CODE][$token->getToken()] = $token->getAlias();
        }

        return $this->tokens;
    }

    /**
     * @return array
     */
    public function getCardTokenSaved()
    {
        $cards = [];

        foreach ($this->getTokensBuilder()->build() as $token) {
            $cards[$token->getMethod()]['brand'] = $token->getBrand();
            $cards[$token->getMethod()]['card_alias'] = $token->getAlias();
            $cards[$token->getMethod()]['card_holder_name'] = $token->getData('card_holder_name');
            $cards[$token->getMethod()]['cpf'] = $token->getTaxvat();
            $cards[$token->getMethod()]['provider'] = $token->getProvider();
            $cards[$token->getMethod()]['token'] = $token->getToken();
        }

        return $cards;
    }

    /**
     * @return mixed
     */
    protected function getInstallmentsBuilder()
    {
        return $this->installmentsBuilder;
    }

    /**
     * @param InstallmentsBuilder $installmentsBuilder
     *
     * @return $this
     */
    protected function setInstallmentsBuilder(InstallmentsBuilder $installmentsBuilder)
    {
        $this->installmentsBuilder = $installmentsBuilder;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTokensBuilder()
    {
        return $this->tokensBuilder;
    }

    /**
     * @param TokensBuilder $tokensBuilder
     *
     * @return $this
     */
    protected function setTokensBuilder(TokensBuilder $tokensBuilder)
    {
        $this->tokensBuilder = $tokensBuilder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    /**
     * @param InstallmentsConfigInterface $installmentsConfig
     *
     * @return $this
     */
    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }
}
