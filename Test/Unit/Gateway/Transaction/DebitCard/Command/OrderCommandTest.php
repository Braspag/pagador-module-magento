<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Command\OrderCommand;

class OrderCommandTest extends \PHPUnit\Framework\TestCase
{
	protected $command;

    public function setUp()
    {
    	$this->apiMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\FacadeInterface');
    	$this->requestBuilderMock = $this->createMock('Magento\Payment\Gateway\Request\BuilderInterface');
    	$this->responseHandlerMock = $this->createMock('Magento\Payment\Gateway\Response\HandlerInterface');
        $this->validatorMock = $this->createMock('Magento\Payment\Gateway\Validator\ValidatorInterface');
    }

    public function tearDown()
    {

    }

    public function testExecute()
    {
    	$this->command = new OrderCommand(
    		$this->apiMock,
    		$this->requestBuilderMock,
    		$this->responseHandlerMock,
            null
    	);

    	$buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\ResponseInterface')
            ->getMock();

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendDebit')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

    	$this->command->execute($buildObject);
    }

    public function testExecuteWithValidator()
    {
        $this->command = new OrderCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->responseHandlerMock,
            $this->validatorMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\ResponseInterface')
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['response' => $responseMock]))
            ->will($this->returnValue($resultMock));

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendDebit')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException Magento\Payment\Gateway\Command\CommandException
     * @expectedExceptionMessage Error Message
     */
    public function testExecuteWithValidatorError()
    {
        $this->command = new OrderCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->responseHandlerMock,
            $this->validatorMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\ResponseInterface')
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $resultMock->expects($this->once())
            ->method('getFailsDescription')
            ->will($this->returnValue(['Error Message']));

        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['response' => $responseMock]))
            ->will($this->returnValue($resultMock));

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendDebit')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->command->execute($buildObject);
    }
}
