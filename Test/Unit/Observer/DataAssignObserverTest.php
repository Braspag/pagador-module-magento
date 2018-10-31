<?php

namespace Webjump\BraspagPagador\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Webjump\BraspagPagador\Observer\DataAssignObserver;

class DataAssignObserverTest extends \PHPUnit\Framework\TestCase
{
    public function testExectute()
    {
        $this->markTestIncomplete();

        $observerContainer = $this->getMockBuilder(Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();

        $observer = new DataAssignObserver();
        $observer->execute($observerContainer);
    }
}
