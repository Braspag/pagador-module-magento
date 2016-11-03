<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentFactory;

class InstallmentFactoryTest extends \PHPUnit_Framework_TestCase
{
	private $factory;

	private $installmentsConfigMock;

	private $objectManagerMock;

    public function setUp()
    {
    	$this->installmentsConfigMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface');

    	$this->objectManagerMock = $this->getMockBuilder('Magento\Framework\ObjectManagerInterface')
            ->getMockForAbstractClass();

        $this->pricingHelper = $this->getMockBuilder('Magento\Framework\Pricing\Helper\Data')
        	->disableOriginalConstructor()
            ->setMethods(array('currency'))
            ->getMock();

    	$this->factory = new InstallmentFactory(
    		$this->objectManagerMock,
    		$this->pricingHelper
    	);
    }

    public function tearDown()
    {

    }

    public function testCreate()
    {
    	$installmentMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface');

    	$installmentMock->expects($this->once())
    	    ->method('setId')
    	    ->with(1);

    	$installmentMock->expects($this->once())
    	    ->method('setLabel')
    	    ->with('1x R$100,00 without interest');

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\Installment', [])
            ->willReturn($installmentMock);

        $this->pricingHelper->expects($this->once())
            ->method('currency')
            ->with(100.00, true, false)
            ->will($this->returnValue('R$100,00'));

        $this->installmentsConfigMock->expects($this->once())
            ->method('isWithInterest')
            ->will($this->returnValue(false));

    	$result = $this->factory->create(1, 100.00, $this->installmentsConfigMock);

    	static::assertSame($installmentMock, $result);
    }

    public function testCreateWithInterest()
    {
    	$installmentMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface');

    	$installmentMock->expects($this->once())
    	    ->method('setId')
    	    ->with(1);

    	$installmentMock->expects($this->once())
    	    ->method('setLabel')
    	    ->with('1x R$120,00 with interest*');

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\Installment', [])
            ->willReturn($installmentMock);

        $this->pricingHelper->expects($this->once())
            ->method('currency')
            ->with(120.00, true, false)
            ->will($this->returnValue('R$120,00'));

        $this->installmentsConfigMock->expects($this->once())
            ->method('isWithInterest')
            ->will($this->returnValue(true));

        $this->installmentsConfigMock->expects($this->once())
            ->method('getInterestRate')
            ->will($this->returnValue(0.2));// 20%

    	$result = $this->factory->create(1, 100.00, $this->installmentsConfigMock);

    	static::assertSame($installmentMock, $result);
    }
}
