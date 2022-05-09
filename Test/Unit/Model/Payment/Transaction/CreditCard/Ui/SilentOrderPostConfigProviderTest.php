<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\SilentOrderPostConfigProvider;
use Magento\Framework\Phrase;

class SilentOrderPostConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;

    private $builderComposite;

	public function setUp()
	{
        $this->silentorderPostBuilderMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\BuilderInterface');

        $this->silentorderPostConfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfigInterface');

		$this->configProvider = new SilentOrderPostConfigProvider(
            'braspag_pagador_creditcard',
            $this->silentorderPostBuilderMock,
            $this->silentorderPostConfigMock
        );
	}

    public function testGetConfig()
    {
        $token = 'ZTJlNDk1YzUtNzMwYy00ZjlkLTkzZTYtOWM5YWQxYTQ1YTc0LTIwOTE3NjI0NDY=';
        $code = \Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider::CODE;

        $this->silentorderPostBuilderMock->expects($this->once())
            ->method('build')
            ->will($this->returnValue($token));

        $this->silentorderPostConfigMock->expects($this->once())
            ->method('isActive')
            ->will($this->returnValue(true));

        $this->silentorderPostConfigMock->expects($this->once())
            ->method('getPaymentTokenUrl')
            ->will($this->returnValue('http://test.com.br'));


        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'silentorderpost' => [
                            'accesstoken' => ['braspag_pagador_creditcard' => $token],
                            'active' => ['braspag_pagador_creditcard' => true],
                            'url' => ['braspag_pagador_creditcard' => 'http://test.com.br'],
                        ],
                    ]
                ]
            ],

            $this->configProvider->getConfig()
        );
    }

    public function testGetConfigDisabled()
    {
        $this->silentorderPostBuilderMock->expects($this->never())
            ->method('build');

        $this->silentorderPostConfigMock->expects($this->once())
            ->method('isActive')
            ->will($this->returnValue(false));

        $this->silentorderPostConfigMock->expects($this->never())
            ->method('getPaymentTokenUrl');


        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'silentorderpost' => [
                            'active' => ['braspag_pagador_creditcard' => false]
                        ],
                    ]
                ]
            ],

            $this->configProvider->getConfig()
        );
    }
}
