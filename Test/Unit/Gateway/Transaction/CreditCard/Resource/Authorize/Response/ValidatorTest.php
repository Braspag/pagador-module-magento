<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	private $validator;

    public function setUp()
    {
    	$this->result = $this->getMock('Magento\Payment\Gateway\Validator\ResultInterface');

    	$this->validator = new Validator(
    		$this->result
    	);
    }

    public function tearDown()
    {

    }

    public function testValidate()
    {
    	$responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

    	$responseMock->expects($this->once())
    	    ->method('getPaymentStatus')
    	    ->will($this->returnValue(2));

    	$result = $this->validator->validate(
    		['response' => $responseMock]
    	);

    	static::assertTrue($result->isValid());
    }

    public function testValidateWithError()
    {
    	$responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

    	$responseMock->expects($this->once())
    	    ->method('getPaymentStatus')
    	    ->will($this->returnValue(0));

    	$responseMock->expects($this->once())
    	    ->method('getPaymentProviderReturnMessage')
    	    ->will($this->returnValue('Error Message'));

    	$result = $this->validator->validate(
    		['response' => $responseMock]
    	);

    	static::assertFalse($result->isValid());
    	static::assertEquals(['Error Message'], $result->getFailsDescription());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Credit Card Authorize Response object should be provided
     */
    public function testValidateWithoutResponse()
    {
        $responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $this->validator->validate([]);
    }
}
