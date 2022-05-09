<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\InstallmentsConfigProvider;
use Magento\Framework\Phrase;

class InstallmentsConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;

    private $builderComposite;

    private $code;

	public function setUp()
	{
        $this->installmentsBuilderMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface');
        $this->installmentsConfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

		$this->configProvider = new InstallmentsConfigProvider(
            $this->installmentsBuilderMock,
            $this->installmentsConfigMock
        );
	}

    public function testGetConfig()
    {
        $installments1 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installments1->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $installments1->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('1x R$10,00 without interest')));

        $installments2 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installments2->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $installments2->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue(__('2x R$5,00 without interest')));

        $installments3 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

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

        $this->installmentsConfigMock->expects($this->exactly(2))
            ->method('isActive')
            ->will($this->returnValue(true));

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

        $this->installmentsConfigMock->expects($this->exactly(2))
            ->method('isActive')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcard' => true],
                            'list' => [],
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }

    public function testGetConfigInstallmentsDisabled()
    {
        $installments1 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $this->installmentsBuilderMock->expects($this->never())
            ->method('build');

        $this->installmentsConfigMock->expects($this->exactly(2))
            ->method('isActive')
            ->will($this->returnValue(false));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'active' => ['braspag_pagador_creditcard' => false],
                            'list' => [],
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
