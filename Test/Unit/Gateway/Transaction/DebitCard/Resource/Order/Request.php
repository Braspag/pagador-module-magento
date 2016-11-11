<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order;


class Request extends \PHPUnit_Framework_TestCase
{
	protected $request;

    public function setUp()
    {
    	$this->request = new Request();
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->getMock();	

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setPaymentData($infoMock);

        
    }
}
