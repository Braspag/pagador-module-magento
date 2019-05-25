<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\Base\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;
	private $baseConfig;

	public function setUp()
	{
		$this->baseConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface');
		$this->creditCardConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');
		$this->debitCardConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
			$this->baseConfig,
            $this->creditCardConfig,
            $this->debitCardConfig
		);
	}

    public function testGetConfig()
    {
    	$this->baseConfig->expects($this->once())
    	    ->method('getMerchantId')
    	    ->will($this->returnValue('BC5D3432-527F-40C6-84BF-C549285536BE'));

        static::assertEquals(
            [
	            'payment' => [
	                'braspag' => [
	                    'merchantId' => 'BC5D3432-527F-40C6-84BF-C549285536BE',
                        'merchantKey' => null
	                ]
	            ],
            ],
            $this->configProvider->getConfig()
        );
    }
}
