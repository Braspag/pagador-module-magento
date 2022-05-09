<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Builder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentFactoryInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Magento\Framework\App\State;
use \Magento\Backend\Model\Session\Quote as SessionAdmin;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var InstallmentFactoryInterface
     */
    private $installmentFactoryMock;

    /**
     * @var InstallmentsConfigInterface
     */
    private $installmentsConfigMock;

    /**
     * @var State
     */
    private $sessionMock;

    /**
     * @var State
     */
    private $stateMock;

    /**
     * @var SessionAdmin
     */
    private $sessionAdminMock;

    public function setUp()
    {
        $this->installmentFactoryMock = $this->createMock(InstallmentFactoryInterface::class);

        $this->installmentsConfigMock = $this->createMock(InstallmentsConfigInterface::class);
        $this->stateMock = $this->createMock(State::class);
        $this->sessionAdminMock = $this->createMock(SessionAdmin::class);

        $this->sessionMock = $this->getMockBuilder('Magento\Checkout\Model\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $this->builder = new Builder(
            $this->installmentFactoryMock,
            $this->installmentsConfigMock,
            $this->sessionMock,
            $this->stateMock,
            $this->sessionAdminMock
        );        
    }

    public function testBuildAsFrontendSession()
    {
        $areaCodeMock = "frontend";
        $minAmoutMock = 3;
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue($minAmoutMock));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('100.00'));

        $this->stateMock
            ->expects($this->atLeastOnce())
            ->method('getAreaCode')
            ->willReturn($areaCodeMock);

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

    public function testBuildAsFrontendSessionWithInstallMinAmount()
    {
        $areaCodeMock = "frontend";
        $minAmoutMock = 3;
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue($minAmoutMock));

        $this->installmentsConfigMock->expects($this->exactly(1))
            ->method('getInstallmentMinAmount')
            ->will($this->returnValue($minAmoutMock));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('5.00'));

        $this->stateMock
            ->expects($this->exactly($minAmoutMock))
            ->method('getAreaCode')
            ->willReturn($areaCodeMock);

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

    public function testBuildBackendSession()
    {
        $areaCodeMock = "adminhtml";
        $minAmoutMock = 3;
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue($minAmoutMock));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('100.00'));

        $this->stateMock
            ->expects($this->atLeastOnce())
            ->method('getAreaCode')
            ->willReturn($areaCodeMock);

        $this->sessionAdminMock->expects($this->once())
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

    public function testBuildAsBackendSessionWithInstallMinAmount()
    {
        $areaCodeMock = "adminhtml";
        $minAmoutMock = 3;
        $this->installmentsConfigMock->expects($this->once())
            ->method('getInstallmentsNumber')
            ->will($this->returnValue($minAmoutMock));

        $this->installmentsConfigMock->expects($this->exactly(1))
            ->method('getInstallmentMinAmount')
            ->will($this->returnValue($minAmoutMock));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->setMethods(['getGrandTotal'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->once())
            ->method('getGrandTotal')
            ->will($this->returnValue('5.00'));

        $this->stateMock
            ->expects($this->exactly($minAmoutMock))
            ->method('getAreaCode')
            ->willReturn($areaCodeMock);

        $this->sessionAdminMock->expects($this->once())
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
