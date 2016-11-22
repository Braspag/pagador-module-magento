<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\SilentOrderPostConfigProvider;
use Magento\Framework\Phrase;

class SilentOrderPostConfigProviderTest extends \PHPUnit_Framework_TestCase
{
	private $configProvider;

    private $builderComposite;

	public function setUp()
	{
        $this->silentorderPostBuilderMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\SilentOrderPost\BuilderInterface');

		$this->configProvider = new SilentOrderPostConfigProvider(
            $this->silentorderPostBuilderMock
        );
	}

    public function testGetConfig()
    {
        $token = 'ZTJlNDk1YzUtNzMwYy00ZjlkLTkzZTYtOWM5YWQxYTQ1YTc0LTIwOTE3NjI0NDY=';

        $this->silentorderPostBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue($token));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'silentorderpost' => [
                            'accesstoken' => ['braspag_pagador_creditcard' => $token],
                            'requesttimeout' => ['braspag_pagador_creditcard' => 5000],
                            'environment' => ['braspag_pagador_creditcard' => 'sandbox'],
                            'language' => ['braspag_pagador_creditcard' => 'PT'],
                        ],
                    ]
                ]
            ],

            $this->configProvider->getConfig()
        );
    }
}
