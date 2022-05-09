<?php

namespace Webjump\BraspagPagador\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Webjump\BraspagPagador\Observer\DataAssignObserver;
use Magento\Framework\Event\Observer;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;

class DataAssignObserverTest extends \PHPUnit\Framework\TestCase
{
    protected $_observerMock;
    protected $_methodInterfaceMock;
    protected $_cardTokenRepositoryInterfaceMock;
    protected $_eventMock;
    protected $_infoInterfaceMock;
    protected $_dataObjectMock;

    public function setUp()
    {
        $this->_observerMock = $this->createMock(Observer::class);

        $this->_methodInterfaceMock = $this->getMockForAbstractClass(
            MethodInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getInfoInstance', 'getCode']
        );
        $this->_cardTokenRepositoryInterfaceMock = $this->createMock(CardTokenRepositoryInterface::class);
        $this->_eventMock = $this->createMock(Event::class);
        $this->_infoInterfaceMock = $this->getMockForAbstractClass(
            MethodInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['unsetData', 'unsAdditionalInformation', 'getAdditionalInformation', 'getMethodInstance', 'addData', 'setAdditionalInformation']
        );
        $this->_dataObjectMock = $this->createMock(DataObject::class);
    }

    public function testExecuteShouldReturnBillingConfig()
    {
        $codeMock = 'braspag_pagador_boleto';

        $this->_observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($this->_eventMock);

        $this->_eventMock->expects($this->once())
            ->method('getDataByKey')
            ->willReturn($this->_methodInterfaceMock);

        $this->_methodInterfaceMock->expects($this->once())
            ->method('getInfoInstance')
            ->willReturn($this->_infoInterfaceMock);

        $this->_infoInterfaceMock->expects($this->once())
            ->method('getAdditionalInformation')
            ->willReturn(['0' => 1]);


        $this->_infoInterfaceMock->expects($this->once())
            ->method('getMethodInstance')
            ->willReturn($this->_methodInterfaceMock);

        $this->_methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->willReturn($codeMock);

        $observer = new DataAssignObserver($this->_cardTokenRepositoryInterfaceMock);
        $observer->execute($this->_observerMock);
    }

    public function testExecuteShouldReturnDataConfig()
    {
        $codeMock = 'teste';

        $dataMock = [
            'cc_type',
            'cc_owner',
            'cc_number',
            'cc_last_4',
            'cc_cid',
            'cc_exp_month',
            'cc_exp_year',
            'cc_provider'
        ];

        $this->_observerMock->expects($this->any())
            ->method('getEvent')
            ->willReturn($this->_eventMock);

        $this->_eventMock->expects($this->exactly(2))
            ->method('getDataByKey')
            ->willReturnOnConsecutiveCalls($this->_methodInterfaceMock, $this->_dataObjectMock);

        $this->_methodInterfaceMock->expects($this->once())
            ->method('getInfoInstance')
            ->willReturn($this->_infoInterfaceMock);

        $this->_infoInterfaceMock->expects($this->once())
            ->method('getAdditionalInformation')
            ->willReturn(['0' => 1]);

        $this->_infoInterfaceMock->expects($this->once())
            ->method('getMethodInstance')
            ->willReturn($this->_methodInterfaceMock);

        $this->_methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->willReturn($codeMock);

        $this->_dataObjectMock->expects($this->once())
            ->method('getData')
            ->willReturn($dataMock);

        $observer = new DataAssignObserver($this->_cardTokenRepositoryInterfaceMock);
        $observer->execute($this->_observerMock);
    }
}
