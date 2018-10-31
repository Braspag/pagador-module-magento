<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    private $configMock;

    public function setUp()
    {
        $this->configMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

    	$this->request = $objectManager->getObject(Request::class, [
    	    'config' => $this->configMock
        ]);
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

        $orderAdapterMock = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $this->request->setOrderAdapter($orderAdapterMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals(['amount' => 0], $this->request->getAdditionalRequest());
    }
}
