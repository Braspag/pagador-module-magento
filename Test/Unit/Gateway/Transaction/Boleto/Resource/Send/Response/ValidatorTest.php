<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Resource\Send\Response;

use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as BoletoConfigInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Response\Validator;
use Magento\Payment\Gateway\Validator\Result;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $responseMock;
	private $boletoConfigInterface;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->responseMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getPaymentUrl',
                'getPaymentBoletoNumber',
                'getPaymentBarCodeNumber',
                'getPaymentPaymentId',
                'getPaymentReceivedDate',
                'getPaymentReasonCode',
                'getPaymentReasonMessage',
                'getPaymentStatus',
                'getPaymentLinks',
                'getDigitableLine',
                'getExpirationDate',
                'getPaymentProviderReturnMessage'
            ])
            ->getMock();

    	$this->boletoConfigInterface = $this->createMock(BoletoConfigInterface::class);

    	$this->validator = new Validator(
            $this->boletoConfigInterface
    	);
    }

    public function testValidateWithSuccess()
    {
        $this->responseMock->expects($this->once())
    	    ->method('getPaymentStatus')
    	    ->will($this->returnValue(2));

    	$result = $this->validator->validate(
    		['response' => $this->responseMock]
    	);

    	$this->assertEquals(new Result(true, [[]]), $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Boleto Authorize Response object should be provided
     */
    public function testValidateShouldThrowAnExceptionWhenInvalidResponse()
    {
    	$this->validator->validate([]);
    }

    public function testValidateShouldReturnErrorMessageWhenStatusIsDenied()
    {
        $this->responseMock->expects($this->exactly(1))
            ->method('getPaymentStatus')
            ->will($this->returnValue(13));

        $this->responseMock->expects($this->once())
            ->method('getPaymentProviderReturnMessage')
            ->will($this->returnValue(13));

        $result = $this->validator->validate(
            ['response' => $this->responseMock]
        );

        $this->assertEquals(new Result(false, ['13']), $result);
    }


    public function testValidateShouldReturnErrorMessageWhenStatusIsDeniedAndProviderReturMessageEmpty()
    {
        $this->responseMock->expects($this->exactly(2))
            ->method('getPaymentStatus')
            ->will($this->returnValue(13));

        $this->responseMock->expects($this->once())
            ->method('getPaymentProviderReturnMessage')
            ->will($this->returnValue( ''));

        $result = $this->validator->validate(
            ['response' => $this->responseMock]
        );

        $this->assertEquals(new Result(false, ['Boleto Payment Failure. #BP13']), $result);
    }

}
