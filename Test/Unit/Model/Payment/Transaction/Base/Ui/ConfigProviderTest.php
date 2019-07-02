<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\Base\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;
	private $BaseConfig;

	public function setUp()
	{
		$this->BaseConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
			$this->BaseConfig
		);
	}

    public function testGetConfig()
    {
        static::assertEquals(
            [
	            'payment' => [
	                'braspag' => [
	                    'merchantId' => '',
                        'merchantKey' => ''
	                ]
	            ],
            ],
            $this->configProvider->getConfig()
        );
    }
}
