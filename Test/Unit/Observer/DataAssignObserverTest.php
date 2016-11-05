<?php

namespace Webjump\BraspagPagador\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Webjump\BraspagPagador\Observer\DataAssignObserver;

class DataAssignObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testExectute()
    {
        $observerContainer = $this->getMockBuilder(Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paymentMethodFacade = $this->getMock(MethodInterface::class);
        $paymentInfoModel = $this->getMock(InfoInterface::class);
        $dataObject = new DataObject(
            [
                'cc_owner' => 'John Due',
                'cc_installments' => 2,
            ]
        );

        $observerContainer->expects(static::atLeastOnce())
            ->method('getEvent')
            ->willReturn($event);
        
        $event->expects(static::exactly(2))
            ->method('getDataByKey')
            ->willReturnMap(
                [
                    [AbstractDataAssignObserver::METHOD_CODE, $paymentMethodFacade],
                    [AbstractDataAssignObserver::DATA_CODE, $dataObject]
                ]
            );

        $paymentMethodFacade->expects(static::once())
            ->method('getInfoInstance')
            ->willReturn($paymentInfoModel);

        $paymentInfoModel->expects(static::at(0))
            ->method('setAdditionalInformation')
            ->with(
                'cc_owner',
                'John Due'
            );

        $paymentInfoModel->expects(static::at(1))
            ->method('setAdditionalInformation')
            ->with(
                'cc_installments',
                2
            );

        $observer = new DataAssignObserver();
        $observer->execute($observerContainer);
    }
}
