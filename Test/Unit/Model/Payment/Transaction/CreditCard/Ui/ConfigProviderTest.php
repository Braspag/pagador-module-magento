<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;
use Magento\Framework\Phrase;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
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

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
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
