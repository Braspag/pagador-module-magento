<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Command;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command\CaptureCommand;

class CaptureCommandTest extends \PHPUnit\Framework\TestCase
{
    private $command;
    private $apiMock;
    private $requestHandlerMock;
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
    	$this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            null,
            null
    	);

    	$buildObject = [];

    	$requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface')
            ->getMock();

    	$this->requestBuilderMock->expects($this->once())
    	    ->method('build')
    	    ->with($buildObject)
    	    ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('captureCreditCard')
            ->with($requestMock)
            ->will($this->returnValue($responseMock));

        $this->responseHandlerMock->expects($this->once())
            ->method('handle')
            ->with($buildObject, ['response' => $responseMock]);

    	$this->command->execute($buildObject);
    }

	public function testExecuteWithValidator()
    {
        $this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface')
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
            ->method('captureCreditCard')
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
        $this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface')
            ->setMethods(
                [
                    'getStatus',
                    'getPaymentAuthenticate',
                    'getReasonCode',
                    'getReasonMessage',
                    'getProviderReturnCode',
                    'getProviderReturnMessage',
                    'getLinks'
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
        $this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            $this->validatorRequestMock,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->getMock();

        $responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface')
            ->setMethods(
                [
                    'getStatus',
                    'getPaymentAuthenticate',
                    'getReasonCode',
                    'getReasonMessage',
                    'getProviderReturnCode',
                    'getProviderReturnMessage',
                    'getLinks'
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
            ->method('captureCreditCard')
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
     * @expectedExceptionMessage Braspag Credit Card Request Lib object should be provided
     */
    public function testExecuteWithInterfaceWrong()
    {
        $this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            null,
            $this->validatorResponseMock
        );

        $buildObject = [];

        $requestWrongMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\AuthRequestInterface')
            ->getMock();

        $this->command->execute($buildObject);
    }

    /**
     * @expectedException \Magento\Sales\Exception\CouldNotInvoiceException
     */
    public function testExecuteWithGuzzleException()
    {
        $this->command = new CaptureCommand(
            $this->apiMock,
            $this->requestBuilderMock,
            $this->requestHandlerMock,
            $this->responseHandlerMock,
            null,
            $this->validatorResponseMock
        );

        $buildObject = [];
        $message ="Any Error Message";
        $requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->getMock();

        $psrRequestInterface = $this->createMock(\Psr\Http\Message\RequestInterface::class);

        $this->requestBuilderMock->expects($this->once())
            ->method('build')
            ->with($buildObject)
            ->will($this->returnValue($requestMock));

        $this->apiMock->expects($this->once())
            ->method('captureCreditCard')
            ->with($requestMock)
            ->willThrowException(new \GuzzleHttp\Exception\ClientException($message, $psrRequestInterface));

        $this->command->execute($buildObject);
    }

}
