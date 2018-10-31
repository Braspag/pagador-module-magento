<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Command\SendCommand;

class SendCommandTest extends \PHPUnit\Framework\TestCase
{
	private $command;
	private $apiMock;
	private $responseHandlerMock;

    public function setUp()
    {
    	$this->apiMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\FacadeInterface');
    	$this->requestBuilderMock = $this->createMock('Magento\Payment\Gateway\Request\BuilderInterface');
    	$this->responseHandlerMock = $this->createMock('Magento\Payment\Gateway\Response\HandlerInterface');

    	$this->command = new SendCommand(
    		$this->apiMock,
    		$this->requestBuilderMock,
    		$this->responseHandlerMock
    	);
    }

    public function tearDown()
    {

    }

    public function testExecute()
    {
    	$buildObject = [];

    	$requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\ResponseInterface')
            ->getMock();

    	$this->requestBuilderMock->expects($this->once())
    	    ->method('build')
    	    ->with($buildObject)
    	    ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendBillet')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

    	$this->command->execute($buildObject);
    }
}
