<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order\Request;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as DebitCardConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Request\Validator;
use Magento\Payment\Gateway\Validator\Result;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $requestMock;
	private $debitCardConfigInterface;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\DebitCard\Send\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getMerchantKey',
                'getMerchantId',
                'isTestEnvironment',
                'getMerchantOrderId',
                'getCustomerName',
                'getPaymentAmount',
                'getPaymentProvider',
                'getPaymentReturnUrl',
                'getPaymentDebitCardCardNumber',
                'getPaymentDebitCardHolder',
                'getPaymentAuthenticate',
                'getPaymentDebitCardExpirationDate',
                'getPaymentDebitCardSecurityCode',
                'getPaymentDebitCardBrand',
                'getPaymentDebitSoptpaymenttoken',
                'getPaymentDebitCardSaveCard',
                'getPaymentExternalAuthenticationFailureType',
                'getPaymentExternalAuthenticationCavv',
                'getPaymentExternalAuthenticationXid',
                'getPaymentExternalAuthenticationEci',
                'getPaymentCardExternalAuthenticationVersion',
                'getPaymentExternalAuthenticationReferenceId',
                'getPaymentCreditSoptpaymenttoken',
                'getPaymentCreditCardBrand',
                'getPaymentCreditCardSaveCard'
            ])
            ->getMock();

    	$this->debitCardConfigInterface = $this->createMock(DebitCardConfigInterface::class);

    	$this->validator = new Validator(
            $this->debitCardConfigInterface
    	);
    }

    public function testValidateWithSuccess()
    {
    	$result = $this->validator->validate(
    		['request' => $this->requestMock]
    	);

    	$this->assertEquals(new Result(true, [[]]), $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag Debit Order Request object should be provided
     */
    public function testValidateShouldThrowAnExceptionWhenInvalidRequest()
    {
    	$this->validator->validate([]);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsError()
    {
        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnError')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(4);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Debit Card Payment Failure. #MPI4"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsFailure()
    {
        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnFailure')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(1);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Debit Card Payment Failure. #MPI1"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsUnenrolled()
    {
        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizeOnUnenrolled')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(2);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Debit Card Payment Failure. #MPI2"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsUnsupportedBrand()
    {
        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizeOnUnsupportedBrand')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(5);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Debit Card Payment Failure. #MPI5"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsDisabled()
    {
        $this->debitCardConfigInterface->expects($this->once())
            ->method('getIsTestEnvironment')
            ->willReturn(false);

        $this->debitCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->requestMock->expects($this->once())
            ->method('getPaymentProvider')
            ->willReturn('rede');

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(6);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Debit Card Payment Failure. #MPI6"]), $result);
    }
}
