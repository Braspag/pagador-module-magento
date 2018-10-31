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
        $this->creditCardConfig = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
            $this->creditCardConfig
        );
	}

    public function testGetConfig()
    {
        $this->creditCardConfig->expects($this->once())
            ->method('isAuthenticate3DsVbv')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isSaveCardActive')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'savecard' => [
                            'active' => ['braspag_pagador_creditcard' => true]
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
