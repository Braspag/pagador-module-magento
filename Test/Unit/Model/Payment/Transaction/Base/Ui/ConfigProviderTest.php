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
            ->method('getIsTestEnvironment')
            ->will($this->returnValue(true));

        static::assertEquals(
            [
	            'payment' => [
	                'braspag' => [
	                    'merchantId' => '',
                        'merchantKey' => '',
                        'isTestEnvironment' => true
	                ]
	            ],
            ],
            $this->configProvider->getConfig()
        );
    }
}
