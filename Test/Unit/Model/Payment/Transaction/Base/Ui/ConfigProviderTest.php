<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\Base\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;
	private $BaseConfig;

	public function setUp()
	{
		$this->BaseConfig = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
			$this->BaseConfig
		);
	}

    public function testGetConfig()
    {
    	$this->BaseConfig->expects($this->once())
    	    ->method('getMerchantId')
    	    ->will($this->returnValue('BC5D3432-527F-40C6-84BF-C549285536BE'));

        static::assertEquals(
            [
	            'payment' => [
	                'braspag' => [
	                    'merchantId' => 'BC5D3432-527F-40C6-84BF-C549285536BE',
	                ]
	            ],
            ],
            $this->configProvider->getConfig()
        );
    }
}
