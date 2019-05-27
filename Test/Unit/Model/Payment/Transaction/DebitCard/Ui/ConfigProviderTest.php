<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\DebitCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;
	private $debitcardConfig;

	public function setUp()
	{
		$this->debitcardConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
			$this->debitcardConfig
		);
	}

    public function testGetConfig()
    {
    	$this->debitcardConfig->expects($this->once())
    		->method('isSuperDebitoActive')
    		->will($this->returnValue(true));

    	$this->debitcardConfig->expects($this->once())
    		->method('isAuthentication3Ds20Active')
    		->will($this->returnValue(true));

        $this->debitcardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizedOnFailure')
            ->will($this->returnValue(true));

        $this->debitcardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizeOnUnenrolled')
            ->will($this->returnValue(true));

    	$this->debitcardConfig->expects($this->once())
    		->method('getAuthenticate3Ds20Mdd1')
    		->will($this->returnValue('mdd 1'));

    	$this->debitcardConfig->expects($this->once())
    		->method('getAuthenticate3Ds20Mdd2')
    		->will($this->returnValue('mdd 2'));

    	$this->debitcardConfig->expects($this->once())
    		->method('getAuthenticate3Ds20Mdd3')
    		->will($this->returnValue('mdd 3'));

    	$this->debitcardConfig->expects($this->once())
    		->method('getAuthenticate3Ds20Mdd4')
    		->will($this->returnValue('mdd 4'));

    	$this->debitcardConfig->expects($this->once())
    		->method('getAuthenticate3Ds20Mdd5')
    		->will($this->returnValue('mdd 5'));

        static::assertEquals(
            [
                'payment' => [
                    'dcform' => [
                        'superdebito' => [
                            'active' => ['braspag_pagador_debitcard' => true]
                        ],
                        'bpmpi_authenticate' => [
                            'active' => true,
                            'authorize_on_failure' => true,
                            'authorize_on_unenrolled' => true,
                            'mdd1' => 'mdd 1',
                            'mdd2' => 'mdd 2',
                            'mdd3' => 'mdd 3',
                            'mdd4' => 'mdd 4',
                            'mdd5' => 'mdd 5'
                        ]
                    ],
                    'redirect_after_place_order' => null
                ]
            ],
            $this->configProvider->getConfig()
        );
    }

    public function testGetConfigDisabled()
    {
    	$this->debitcardConfig->expects($this->once())
    		->method('isSuperDebitoActive')
    		->will($this->returnValue(false));

        $this->debitcardConfig->expects($this->once())
            ->method('isAuthentication3Ds20Active')
            ->will($this->returnValue(true));

        $this->debitcardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizedOnFailure')
            ->will($this->returnValue(true));

        $this->debitcardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizeOnUnenrolled')
            ->will($this->returnValue(true));

        $this->debitcardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd1')
            ->will($this->returnValue('mdd 1'));

        $this->debitcardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd2')
            ->will($this->returnValue('mdd 2'));

        $this->debitcardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd3')
            ->will($this->returnValue('mdd 3'));

        $this->debitcardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd4')
            ->will($this->returnValue('mdd 4'));

        $this->debitcardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd5')
            ->will($this->returnValue('mdd 5'));

        static::assertEquals(
            [
                'payment' => [
                    'dcform' => [
                        'superdebito' => [
                            'active' => ['braspag_pagador_debitcard' => false]
                        ],
                        'bpmpi_authenticate' => [
                            'active' => true,
                            'authorize_on_failure' => true,
                            'authorize_on_unenrolled' => true,
                            'mdd1' => 'mdd 1',
                            'mdd2' => 'mdd 2',
                            'mdd3' => 'mdd 3',
                            'mdd4' => 'mdd 4',
                            'mdd5' => 'mdd 5'
                        ]
                    ],
                    'redirect_after_place_order' => null
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
