<?php

namespace Braspag\BraspagPagador\Test\Unit\Gateway\Transaction;

use Braspag\BraspagPagador\Gateway\Transaction\Boleto\Command\SendCommand;

class SendCommandTest extends \PHPUnit\Framework\TestCase
{
    private $command;
    private $apiMock;
    private $responseHandlerMock;
    private $requestHandlerMock;

    public function setUp()
    {
        $this->apiMock = $this->createMock('Braspag\Braspag\Pagador\Transaction\FacadeInterface');
        $this->requestBuilderMock = $this->createMock('Magento\Payment\Gateway\Request\BuilderInterface');
        $this->requestHandlerMock = $this->createMock('Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface');
        $this->responseHandlerMock = $this->createMock('Magento\Payment\Gateway\Response\HandlerInterface');

        $this->command = new SendCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock
        );
    }

    public function tearDown()
    {
    }

    public function testExecute()
    {
        $buildObject = [];

        $requestMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\Boleto\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface')
            ->getMock();

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendBoleto')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function testExecuteShouldThrowAnExceptionWhenInvalidRequest()
    {
        $buildObject = [];

        $requestMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\Boleto\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Braspag\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface')
            ->getMock();

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue(false));

        $this->command->execute($buildObject);
    }
}