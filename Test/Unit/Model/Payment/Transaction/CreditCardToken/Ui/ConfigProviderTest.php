<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCardToken\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCardToken\Ui\ConfigProvider;
use Magento\Framework\Phrase;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
	private $configProvider;

    private $builderComposite;

	public function setUp()
	{
        $this->installmentsBuilderMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\BuilderInterface');
        $this->tokensBuilderMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Tokens\BuilderInterface');
        $this->installmentsConfigMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface');

		$this->configProvider = new ConfigProvider(
            $this->installmentsBuilderMock,
            $this->tokensBuilderMock,
            $this->installmentsConfigMock
        );
	}

    public function testGetConfig()
    {
        $installments1 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface');

        $installments1->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $installments1->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('1x R$10,00 without interest')));

        $installments2 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface');

        $installments2->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $installments2->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('2x R$5,00 without interest')));

        $installments3 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface');

        $installments3->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(3));

        $installments3->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('3x R$3,80 with interest*')));

        $this->installmentsBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue([
                $installments1,
                $installments2,
                $installments3,
            ]));

        $token1 = $this->getMock('Webjump\BraspagPagador\Api\Data\CardTokenInterface');

        $token1->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue('token 1'));

        $token1->expects($this->once())
            ->method('getAlias')
            ->will($this->returnValue(__('alias 1')));

        $token2 = $this->getMock('Webjump\BraspagPagador\Api\Data\CardTokenInterface');

        $token2->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue('token 2'));

        $token2->expects($this->once())
            ->method('getAlias')
            ->will($this->returnValue(__('alias 2')));

        $this->tokensBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue([
                $token1,
                $token2,
            ]));

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcardtoken' => true],
                            'list' => [
                                'braspag_pagador_creditcardtoken' => [
                                    1 => __('1x R$10,00 without interest'),
                                    2 => __('2x R$5,00 without interest'),
                                    3 => __('3x R$3,80 with interest*'),
                                ],
                            ],
                        ],
                        'tokens' => [
                            'list' => ['braspag_pagador_creditcardtoken' => [
                                'token 1' => __('alias 1'),
                                'token 2' => __('alias 2'),
                            ]],
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }

    public function testGetConfigWithoutInstallments()
    {
        $this->installmentsBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue([]));

        $this->tokensBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue([]));

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcardtoken' => true],
                            'list' => [],
                        ],
                        'tokens' => [
                            'list' => [],
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
