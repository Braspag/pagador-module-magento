<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\Billet\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\Billet\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;
	private $billetConfig;

	public function setUp()
	{
		$this->billetConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
			$this->billetConfig
		);
	}

    public function testGetConfig()
    {
    	$this->billetConfig->expects($this->once())
    	    ->method('getPaymentDemonstrative')
    	    ->will($this->returnValue('Desmonstrative Teste'));

        static::assertEquals(
            [
	            'payment' => [
	                ConfigProvider::CODE => [
	                    'info' => [
	                        'demonstrative' => 'Desmonstrative Teste',
	                    ]
	                ]
	            ],
            ],
            $this->configProvider->getConfig()
        );
    }
}
