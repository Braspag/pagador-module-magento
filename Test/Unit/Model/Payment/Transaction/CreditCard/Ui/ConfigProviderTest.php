<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
	private $configProvider;

    private $ccConfig;

	public function setUp()
	{
        // $this->ccConfig = $this->getMock()
		$this->configProvider = new ConfigProvider();
	}

    public function testGetConfig()
    {
        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'installments' => [
                            'braspag_pagador_creditcard' => [
                                1 => '1 R$ 10,00 with interest*',
                                2 => '2 R$ 20,00 with interest*',
                            ]
                        ],
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
