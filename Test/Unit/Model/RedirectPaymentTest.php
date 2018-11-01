<?php

/**
 *
 * Redirect Payment Test.php
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\RedirectPayment;

class RedirectPaymentTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->orderRepositoryMock = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderPaymentMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Payment::class)
            ->setMethods(['getAdditionalInformation'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->orderPaymentMock);

        $this->model = $objectManager->getObject(
            RedirectPayment::class,
            [
                'OrderRepository' => $this->orderRepositoryMock
            ]
        );
    }

    /** @test */
    public function testgetLink()
    {
        // prepare the test

        $expected = 'http://link.com.br';
        $orderId = 1;
        $additionalInformation = ['redirect_url' => $expected];

        $this->orderRepositoryMock->expects($this->once())
            ->method('get')
            ->with($orderId)
            ->willReturn($this->orderMock);

        $this->orderPaymentMock->expects($this->once())
            ->method('getAdditionalInformation')
            ->will($this->returnValue($additionalInformation));

        // perform the changes

        $result = $this->model->getLink($orderId);

        // test the results

        static::assertEquals($expected, $result);
    }
}
