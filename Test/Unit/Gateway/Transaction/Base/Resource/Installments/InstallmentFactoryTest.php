<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentFactory;

class InstallmentFactoryTest extends \PHPUnit\Framework\TestCase
{
	private $factory;

	private $installmentsConfigMock;

	private $objectManagerMock;

    private $installmentClass;

    public function setUp()
    {
    	$this->installmentsConfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

    	$this->objectManagerMock = $this->getMockBuilder('Magento\Framework\ObjectManagerInterface')
            ->getMockForAbstractClass();

        $this->pricingHelper = $this->getMockBuilder('Magento\Framework\Pricing\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->installmentClass = null;

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
    	$installmentMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

    	$installmentMock->expects($this->once())
    	    ->method('setIndex')
    	    ->with(1);

    	$installmentMock->expects($this->once())
    	    ->method('setPrice')
    	    ->with(100.00);

        $installmentMock->expects($this->once())
            ->method('setWithInterest')
            ->with(false);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment', ['priceHelper' => $this->pricingHelper])
            ->willReturn($installmentMock);

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(false));

    	$result = $this->factory->create(1, 100.00, $this->installmentsConfigMock);

    	static::assertSame($installmentMock, $result);
    }

    public function testCreateWithInterest()
    {
    	$installmentMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installmentMock->expects($this->once())
            ->method('setIndex')
            ->with(1);

        $installmentMock->expects($this->once())
            ->method('setPrice')
            ->with(120.00);

        $installmentMock->expects($this->once())
            ->method('setWithInterest')
            ->with(true);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment', ['priceHelper' => $this->pricingHelper])
            ->willReturn($installmentMock);

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(true));

        $this->installmentsConfigMock->expects($this->once())
            ->method('getInterestRate')
            ->will($this->returnValue(0.2));// 20%

    	$result = $this->factory->create(1, 100.00, $this->installmentsConfigMock);

    	static::assertSame($installmentMock, $result);
    }

    public function testCreateWithInterestWithMaxWithouInterest()
    {
        $installmentMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $installmentMock->expects($this->once())
            ->method('setIndex')
            ->with(3);

        $installmentMock->expects($this->once())
            ->method('setPrice')
            ->with(33.333333333333336);

        $installmentMock->expects($this->once())
            ->method('setWithInterest')
            ->with(false);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment', ['priceHelper' => $this->pricingHelper])
            ->willReturn($installmentMock);

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(true));

        $this->installmentsConfigMock->expects($this->once())
            ->method('getinstallmentsMaxWithoutInterest')
            ->will($this->returnValue(5));

        $result = $this->factory->create(3, 100.00, $this->installmentsConfigMock);

        static::assertSame($installmentMock, $result);
    }

    public function testCreateWithCustomClass()
    {
        $installmentMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $this->factory = new InstallmentFactory(
            $this->objectManagerMock,
            $this->pricingHelper,
            'Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment'
        );

        $installmentMock->expects($this->once())
            ->method('setIndex')
            ->with(1);

        $installmentMock->expects($this->once())
            ->method('setPrice')
            ->with(100.00);

        $installmentMock->expects($this->once())
            ->method('setWithInterest')
            ->with(false);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment', ['priceHelper' => $this->pricingHelper])
            ->willReturn($installmentMock);

        $this->installmentsConfigMock->expects($this->once())
            ->method('isInterestByIssuer')
            ->will($this->returnValue(false));

        $result = $this->factory->create(1, 100.00, $this->installmentsConfigMock);

        static::assertSame($installmentMock, $result);
    }
}
