<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Request;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Request\Validator;
use Magento\Payment\Gateway\Validator\Result;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $requestMock;
	private $creditCardConfigInterface;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getMerchantId',
                'getMerchantKey',
                'isTestEnvironment',
                'getMerchantOrderId',
                'getCustomerName',
                'getCustomerIdentity',
                'getCustomerIdentityType',
                'getCustomerEmail',
                'getCustomerBirthDate',
                'getCustomerAddressStreet',
                'getCustomerAddressNumber',
                'getCustomerAddressComplement',
                'getCustomerAddressZipCode',
                'getCustomerAddressDistrict',
                'getCustomerAddressCity',
                'getCustomerAddressState',
                'getCustomerAddressCountry',
                'getCustomerAddressPhone',
                'getCustomerDeliveryAddressStreet',
                'getCustomerDeliveryAddressNumber',
                'getCustomerDeliveryAddressComplement',
                'getCustomerDeliveryAddressZipCode',
                'getCustomerDeliveryAddressDistrict',
                'getCustomerDeliveryAddressCity',
                'getCustomerDeliveryAddressState',
                'getCustomerDeliveryAddressCountry',
                'getCustomerDeliveryAddressPhone',
                'getPaymentAmount',
                'getPaymentCurrency',
                'getPaymentCountry',
                'getPaymentProvider',
                'getPaymentServiceTaxAmount',
                'getPaymentInstallments',
                'getPaymentInterest',
                'getPaymentCapture',
                'getPaymentAuthenticate',
                'getReturnUrl',
                'getPaymentType',
                'getPaymentSoftDescriptor',
                'getPaymentCreditCardCardNumber',
                'getPaymentCreditCardHolder',
                'getPaymentCreditCardExpirationDate',
                'getPaymentCreditCardSecurityCode',
                'getPaymentCreditCardSaveCard',
                'getPaymentCreditCardBrand',
                'getPaymentExternalAuthenticationFailureType',
                'getPaymentExternalAuthenticationCavv',
                'getPaymentExternalAuthenticationXid',
                'getPaymentExternalAuthenticationEci',
                'getPaymentCardExternalAuthenticationVersion',
                'getPaymentExternalAuthenticationReferenceId',
                'getPaymentExtraDataCollection',
                'getAntiFraudRequest',
                'getPaymentSplitRequest',
                'getPaymentCreditCardCardToken',
                'getPaymentCreditSoptpaymenttoken',
                'getAvsRequest'
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

    	$this->assertEquals(new Result(true, [[]]), $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag CreditCard Authorize Request object should be provided
     */
    public function testValidateShouldThrowAnExceptionWhenInvalidRequest()
    {
    	$this->validator->validate([]);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsError()
    {
        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnError')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(4);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Credit Card Payment Failure. #MPI4"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsFailure()
    {
        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizedOnFailure')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(1);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Credit Card Payment Failure. #MPI1"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsUnenrolled()
    {
        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizeOnUnenrolled')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(2);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Credit Card Payment Failure. #MPI2"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsUnsupportedBrand()
    {
        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20Active')
            ->willReturn(true);

        $this->creditCardConfigInterface->expects($this->once())
            ->method('isAuth3Ds20AuthorizeOnUnsupportedBrand')
            ->willReturn(false);

        $this->requestMock->expects($this->once())
            ->method('getPaymentExternalAuthenticationFailureType')
            ->willReturn(5);

        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(false, ["Credit Card Payment Failure. #MPI5"]), $result);
    }

    public function testValidateShouldReturnErrorMessageWhenAuthentication3DS20ReturnsDisabled()
    {
        $this->creditCardConfigInterface->expects($this->once())
            ->method('getIsTestEnvironment')
            ->willReturn(false);

        $this->creditCardConfigInterface->expects($this->once())
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

        $this->assertEquals(new Result(false, ["Credit Card Payment Failure. #MPI6"]), $result);
    }
}
