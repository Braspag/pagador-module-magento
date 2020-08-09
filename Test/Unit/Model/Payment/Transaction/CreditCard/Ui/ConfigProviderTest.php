<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;
use Magento\Framework\Phrase;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;

    private $creditCardConfig;

	public function setUp()
	{
        $this->creditCardConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
            $this->creditCardConfig
        );
	}

    public function testGetConfig()
    {
        $this->creditCardConfig->expects($this->once())
            ->method('isSaveCardActive')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuth3Ds20MCOnlyNotifyActive')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnError')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnFailure')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuth3Ds20AuthorizeOnUnenrolled')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuth3Ds20Mdd1')
            ->will($this->returnValue('mdd 1'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuth3Ds20Mdd2')
            ->will($this->returnValue('mdd 2'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuth3Ds20Mdd3')
            ->will($this->returnValue('mdd 3'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuth3Ds20Mdd4')
            ->will($this->returnValue('mdd 4'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuth3Ds20Mdd5')
            ->will($this->returnValue('mdd 5'));

        $this->creditCardConfig->expects($this->once())
            ->method('isCardViewActive')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'savecard' => [
                            'active' => ['braspag_pagador_creditcard' => true]
                        ],
                        'bpmpi_authentication' => [
                            'active' => true,
                            'mastercard_notify_only' => true,
                            'authorize_on_error' => true,
                            'authorize_on_failure' => true,
                            'authorize_on_unenrolled' => true,
                            'mdd1' => 'mdd 1',
                            'mdd2' => 'mdd 2',
                            'mdd3' => 'mdd 3',
                            'mdd4' => 'mdd 4',
                            'mdd5' => 'mdd 5'
                        ],
                        'card_view' => [
                            'active' => true
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
