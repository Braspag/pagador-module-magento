<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Response\Validator;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $responseMock;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface');

    	$this->validator = new Validator(
    		$result
    	);
    }

    public function testValidate()
    {
        $this->responseMock->expects($this->once())
    	    ->method('getReasonCode')
    	    ->will($this->returnValue(0));

    	$result = $this->validator->validate(
    		['response' => $this->responseMock]
    	);

    	static::assertTrue($result->isValid());
    }

    public function testValidateWithError()
    {
        $this->responseMock->expects($this->once())
    	    ->method('getReasonCode')
    	    ->will($this->returnValue(2));

        $this->responseMock->expects($this->once())
    	    ->method('getProviderReturnMessage')
    	    ->will($this->returnValue('Error Message'));

    	$result = $this->validator->validate(
    		['response' => $this->responseMock]
    	);

    	static::assertFalse($result->isValid());
    	static::assertEquals(['Error Message'], $result->getFailsDescription());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Credit Card Capture Response object should be provided
     */
    public function testValidateWithoutResponse()
    {
        $this->validator->validate([]);
    }
}
