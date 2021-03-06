<?php

namespace Braspag\BraspagPagador\Test\Unit\Model\Payment\Transaction\Boleto\Ui;

use Braspag\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
    private $configProvider;
    private $boletoConfig;

    public function setUp()
    {
        $this->boletoConfig = $this->createMock('Braspag\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface');

        $this->configProvider = new ConfigProvider(
            $this->boletoConfig
        );
    }

    public function testGetConfig()
    {
        $this->boletoConfig->expects($this->once())
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