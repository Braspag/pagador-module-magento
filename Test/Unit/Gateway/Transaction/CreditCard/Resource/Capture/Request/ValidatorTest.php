<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture\Request;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Request\Validator;
use Magento\Payment\Gateway\Validator\Result;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $requestMock;
	private $creditCardConfigInterface;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getMerchantId',
                'getMerchantKey',
                'isTestEnvironment',
                'getPaymentId',
                'getAdditionalRequest'
            ])
            ->getMock();

    	$this->creditCardConfigInterface = $this->createMock(CreditCardConfigInterface::class);

    	$this->validator = new Validator(
            $this->creditCardConfigInterface
    	);
    }

    public function testValidateWithSuccess()
    {
    	$result = $this->validator->validate(
    		['request' => $this->requestMock]
    	);

    	$this->assertEquals(new Result(true, []), $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Credit Card Capture Request object should be provided
     */
    public function testValidateShouldThrowAnExceptionWhenInvalidRequest()
    {
    	$this->validator->validate([]);
    }
}
