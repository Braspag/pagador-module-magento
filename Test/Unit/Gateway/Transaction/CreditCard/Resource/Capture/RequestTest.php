<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    private $creaditCardConfig;

    public function setUp()
    {
        $this->configMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

    	$this->request = new Request(
            $this->configMock
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->configMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $orderAdapterMock = $this->getMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $orderAdapterMock->expects($this->once())
            ->method('getOrderIncrementId')
            ->will($this->returnValue('2016000001'));

        $this->request->setOrderAdapter($orderAdapterMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals('2016000001', $this->request->getPaymentId());
        static::assertEquals(['amount' => 0], $this->request->getAdditionalRequest());
    }
}
