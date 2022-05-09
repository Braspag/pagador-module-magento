<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Command\OrderCommand;

class OrderCommandTest extends \PHPUnit\Framework\TestCase
{
    private $command;
    private $apiMock;
    private $responseHandlerMock;
    private $validatorRequestMock;
    private $validatorResponseMock;

    public function setUp()
    {
    	$this->apiMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\FacadeInterface');
    	$this->requestBuilderMock = $this->createMock('Magento\Payment\Gateway\Request\BuilderInterface');
    	$this->requestHandlerMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface');
    	$this->responseHandlerMock = $this->createMock('Magento\Payment\Gateway\Response\HandlerInterface');
        $this->validatorRequestMock = $this->createMock('Magento\Payment\Gateway\Validator\ValidatorInterface');
        $this->validatorResponseMock = $this->createMock('Magento\Payment\Gateway\Validator\ValidatorInterface');
    }

    public function tearDown()
    {

    }

    public function testExecute()
    {
    	$this->command = new OrderCommand(
    		$this->apiMock,
    		$this->requestBuilderMock,
    		$this->requestHandlerMock,
    		$this->responseHandlerMock,
            null,
            null
    	);

    	$buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface')
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
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface')
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $resultMock->expects($this->exactly(2))
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->validatorRequestMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['request' => $requestMock]))
            ->will($this->returnValue($resultMock));

        $this->apiMock->expects($this->once())
            ->method('sendDebit')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->validatorResponseMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['response' => $responseMock]))
            ->will($this->returnValue($resultMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Error Message
     */
    public function testExecuteWithValidatorErrorOnRequest()
    {
        $this->command = new OrderCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface')
            ->setMethods(
                [
                    'getPaymentAuthenticationUrl',
                    'getPaymentAcquirerTransactionId',
                    'getPaymentPaymentId',
                    'getPaymentReceivedDate',
                    'getPaymentReasonCode',
                    'getPaymentReasonMessage',
                    'getPaymentStatus',
                    'getPaymentAuthenticate',
                    'getPaymentProviderReturnCode',
                    'getPaymentProviderReturnMessage',
                    'getPaymentLinks',
                    'getPaymentCardProvider'
                ])
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $resultMock->expects($this->once())
            ->method('getFailsDescription')
            ->will($this->returnValue(['Error Message']));

        $this->validatorRequestMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['request' => $requestMock]))
            ->will($this->returnValue($resultMock));

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Error Message
     */
    public function testExecuteWithValidatorErrorOnResponse()
    {
        $this->command = new OrderCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface')
            ->setMethods(
                [
                    'getPaymentAuthenticationUrl',
                    'getPaymentAcquirerTransactionId',
                    'getPaymentPaymentId',
                    'getPaymentReceivedDate',
                    'getPaymentReasonCode',
                    'getPaymentReasonMessage',
                    'getPaymentStatus',
                    'getPaymentAuthenticate',
                    'getPaymentProviderReturnCode',
                    'getPaymentProviderReturnMessage',
                    'getPaymentLinks',
                    'getPaymentCardProvider'
                ])
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $resultMock->expects($this->exactly(2))
            ->method('isValid')
            ->will($this->onConsecutiveCalls(true, false));

        $resultMock->expects($this->once())
            ->method('getFailsDescription')
            ->will($this->returnValue(['Error Message']));

        $resultMock->expects($this->once())
            ->method('getFailsDescription')
            ->will($this->returnValue(['Error Message']));

        $this->validatorRequestMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['request' => $requestMock]))
            ->will($this->returnValue($resultMock));

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('sendDebit')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->validatorResponseMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['response' => $responseMock]))
            ->will($this->returnValue($resultMock));

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Debitcard Order Request Lib object should be provided
     */
    public function testExecuteShouldThrowAnExceptionWhenInvalidRequest()
    {
        $this->command = new OrderCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface')
            ->setMethods(
                [
                    'getPaymentAuthenticationUrl',
                    'getPaymentAcquirerTransactionId',
                    'getPaymentPaymentId',
                    'getPaymentReceivedDate',
                    'getPaymentReasonCode',
                    'getPaymentReasonMessage',
                    'getPaymentStatus',
                    'getPaymentAuthenticate',
                    'getPaymentProviderReturnCode',
                    'getPaymentProviderReturnMessage',
                    'getPaymentLinks',
                    'getPaymentCardProvider'
                ])
            ->getMock();

        $resultMock = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');

        $resultMock->expects($this->exactly(1))
            ->method('isValid')
            ->will($this->onConsecutiveCalls(true, false));

        $this->validatorRequestMock->expects($this->once())
            ->method('validate')
            ->with(array_merge($buildObject, ['request' => false]))
            ->will($this->returnValue($resultMock));

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue(false));

        $this->command->execute($buildObject);
    }
}
