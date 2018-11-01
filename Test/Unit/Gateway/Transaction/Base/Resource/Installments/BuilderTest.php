<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Builder;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    private $builder;

    private $installmentFactoryMock;

    private $installmentsConfigMock;

    private $sessionMock;

    public function setUp()
    {
        $this->installmentFactoryMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentFactoryInterface');

        $this->installmentsConfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

        $this->sessionMock = $this->getMockBuilder('Magento\Checkout\Model\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $this->builder = new Builder(
            $this->installmentFactoryMock,
            $this->installmentsConfigMock,
            $this->sessionMock
        );        
    }

    public function testBuild()
    {
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue(3));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('100.00'));

        $this->sessionMock->expects($this->once())
            ->method('getQuote')
            ->will($this->returnValue($quoteMock));

        $installments1 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');
        $installments2 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');
        $installments3 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $this->installmentFactoryMock->expects($this->at(0))
            ->method('create')
            ->with(1, 100.00, $this->installmentsConfigMock)
            ->will($this->returnValue($installments1));

        $this->installmentFactoryMock->expects($this->at(1))
            ->method('create')
            ->with(2, 100.00, $this->installmentsConfigMock)
            ->will($this->returnValue($installments2));

        $this->installmentFactoryMock->expects($this->at(2))
            ->method('create')
            ->with(3, 100.00, $this->installmentsConfigMock)
            ->will($this->returnValue($installments3));

        $expected = [
            $installments1,
            $installments2,
            $installments3,
        ];

        $result = $this->builder->build();

        static::assertEquals($expected, $result);
    }

    public function testBuildWithInstallMinAmount()
    {
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue(3));

        $this->installmentsConfigMock->expects($this->exactly(1))
            ->method('getInstallmentMinAmount')
            ->will($this->returnValue(3));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('5.00'));

        $this->sessionMock->expects($this->once())
            ->method('getQuote')
            ->will($this->returnValue($quoteMock));

        $installments1 = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface');

        $this->installmentFactoryMock->expects($this->at(0))
            ->method('create')
            ->with(1, 5.00, $this->installmentsConfigMock)
            ->will($this->returnValue($installments1));

        $expected = [
            $installments1,
        ];

        $result = $this->builder->build();

        static::assertEquals($expected, $result);
    }
}
