<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;
use Magento\Framework\Phrase;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
	private $configProvider;

    private $builderComposite;

	public function setUp()
	{
        $this->installmentsBuilderMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface');
        $this->installmentsConfigMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface');
        $this->silentorderPostBuilderMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\SilentOrderPost\BuilderInterface');

		$this->configProvider = new ConfigProvider(
            $this->installmentsBuilderMock,
            $this->installmentsConfigMock,
            $this->silentorderPostBuilderMock
        );
	}

    public function testGetConfig()
    {
        $installments1 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installments1->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $installments1->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('1x R$10,00 without interest')));

        $installments2 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installments2->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $installments2->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('2x R$5,00 without interest')));

        $installments3 = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

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

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcard' => true],
                            'list' => [
                                'braspag_pagador_creditcard' => [
                                    1 => __('1x R$10,00 without interest'),
                                    2 => __('2x R$5,00 without interest'),
                                    3 => __('3x R$3,80 with interest*'),
                                ],
                            ],
                        ],
                        'authenticate' => [
                            'active' => ['braspag_pagador_creditcard' => true]
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

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcard' => true],
                            'list' => [],
                        ],
                        'authenticate' => [
                            'active' => ['braspag_pagador_creditcard' => true]
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
