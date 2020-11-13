<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order\Response;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Response\Validator;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $responseMock;
	private $paymentDataObjectMock;
	private $paymentMock;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$configInterface = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface');
        $this->responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\ResponseInterface');
        $this->paymentDataObjectMock = $this->createMock('Magento\Payment\Gateway\Data\PaymentDataObject');
        $this->paymentMock = $this->createMock('Magento\Sales\Model\Order\Payment');

    	$this->validator = new Validator(
            $configInterface
    	);
    }

    public function testValidate()
    {
    	$this->responseMock->expects($this->once())
    	    ->method('getPaymentStatus')
    	    ->will($this->returnValue(2));

    	$result = $this->validator->validate(
    		[
    		    'response' => $this->responseMock,
                'payment' => $this->paymentDataObjectMock
            ]
    	);

    	static::assertTrue($result->isValid());
    }

    public function testValidateWithError()
    {
    	$this->responseMock->expects($this->once())
    	    ->method('getPaymentStatus')
    	    ->will($this->returnValue(13));

    	$this->responseMock->expects($this->once())
    	    ->method('getPaymentProviderReturnMessage')
    	    ->will($this->returnValue('Error Message'));

        $result = $this->validator->validate(
            [
                'response' => $this->responseMock,
                'payment' => $this->paymentDataObjectMock
            ]
        );

    	static::assertFalse($result->isValid());
    	static::assertEquals(['Error Message'], $result->getFailsDescription());
    }

    public function testValidateWithErrorMessageEmpty()
    {
        $this->responseMock->expects($this->exactly(2))
            ->method('getPaymentStatus')
            ->will($this->returnValue(13));

        $this->responseMock->expects($this->once())
            ->method('getPaymentProviderReturnMessage')
            ->will($this->returnValue(''));

        $result = $this->validator->validate(
            [
                'response' => $this->responseMock,
                'payment' => $this->paymentDataObjectMock
            ]
        );

        static::assertFalse($result->isValid());
        static::assertEquals(['Debit Card Payment Failure. #BP13'], $result->getFailsDescription());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Debit Authorize Response object should be provided
     */
    public function testValidateWithoutResponse()
    {
        $this->validator->validate([]);
    }
}
