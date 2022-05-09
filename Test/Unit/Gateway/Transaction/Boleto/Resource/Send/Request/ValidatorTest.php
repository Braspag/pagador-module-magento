<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Resource\Send\Request;

use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as BoletoConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Request\Validator;
use Magento\Payment\Gateway\Validator\Result;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $validator;
	private $requestMock;
	private $boletoConfigInterface;

    public function setUp()
    {
    	$result = $this->createMock('Magento\Payment\Gateway\Validator\ResultInterface');
    	$this->requestMock = $this->getMockBuilder('Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getMerchantOrderId',
                'getCustomerName',
                'getCustomerIdentity',
                'getCustomerIdentityType',
                'getCustomerEmail',
                'getCustomerBirthDate',
                'getCustomerAddressStreet',
                'getCustomerAddressNumber',
                'getCustomerAddressComplement',
                'getCustomerAddressDistrict',
                'getCustomerAddressZipCode',
                'getCustomerAddressCity',
                'getCustomerAddressState',
                'getCustomerAddressCountry',
                'getPaymentAmount',
                'getPaymentProvider',
                'getPaymentBank',
                'getPaymentAddress',
                'getPaymentDemonstrative',
                'getPaymentExpirationDate',
                'getPaymentIdentification',
                'getPaymentInstructions',
                'getMerchantId',
                'getMerchantKey',
                'isTestEnvironment',
                'getPaymentAssignor',
                'getPaymentBoletoNumber'
            ])
            ->getMock();

    	$this->boletoConfigInterface = $this->createMock(BoletoConfigInterface::class);

    	$this->validator = new Validator(
            $this->boletoConfigInterface
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
     * @expectedExceptionMessage Braspag Boleto Request object should be provided
     */
    public function testValidateShouldThrowAnExceptionWhenInvalidRequest()
    {
    	$this->validator->validate([]);
    }

    public function testValidateShouldReturnErrorMessageWhenStatusIsDenied()
    {
        $result = $this->validator->validate(
            ['request' => $this->requestMock]
        );

        $this->assertEquals(new Result(true, [[]]), $result);
    }
}
