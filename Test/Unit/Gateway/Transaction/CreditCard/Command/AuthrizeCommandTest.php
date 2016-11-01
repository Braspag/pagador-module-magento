<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command\AuthorizeCommand;

class AuthorizeCommandTest extends \PHPUnit_Framework_TestCase
{
	private $command;
	private $apiMock;
	private $responseHandlerMock;

    public function setUp()
    {
    	$this->apiMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\FacadeInterface');
    	$this->requestBuilderMock = $this->getMock('Magento\Payment\Gateway\Request\BuilderInterface');
    	$this->responseHandlerMock = $this->getMock('Magento\Payment\Gateway\Response\HandlerInterface');

    	$this->command = new AuthorizeCommand(
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

    	$requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface')
            ->getMock();

    	$this->requestBuilderMock->expects($this->once())
    	    ->method('build')
    	    ->with($buildObject)
    	    ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendCreditCard')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

    	$this->command->execute($buildObject);
    }
}
