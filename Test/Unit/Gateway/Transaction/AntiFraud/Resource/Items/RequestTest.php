<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\AntiFraud\Resource\Items;

use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\Items\Request;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class RequestTest extends TestCase
{

    /**
     * @var OrderItemInterface
     */
    private $itemAdapterMock;

    /**
     * @var SessionManagerInterface
     */
    private $sessionMock;


    protected function setUp()
    {
        $this->sessionMock = $this->createMock(SessionManagerInterface::class);
        $this->itemAdapterMock = $this->createMock(OrderItemInterface::class);

    }

    /**
     * @return Request
     */
    private function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var Request $model */
        $model = $objectManager->getObject(Request::class, [
            'itemAdapter' => $this->itemAdapterMock,
            'session' => $this->sessionMock
        ]);

        return $model;
    }

    public function testGetName()
    {
        $expectedResult = "John Doe";

        $this->itemAdapterMock
            ->expects($this->once())
            ->method('getName')
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();
        $result = $requestModel->getName();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetQuantity()
    {
        $expectedResult = "10";

        $this->itemAdapterMock
            ->expects($this->once())
            ->method('getQtyOrdered')
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();
        $result = $requestModel->getQuantity();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetSku()
    {
        $expectedResult = "SKUMOCK";

        $this->itemAdapterMock
            ->expects($this->once())
            ->method('getSku')
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();
        $result = $requestModel->getSku();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetUnitPrice()
    {
        $expectedResult = "5990";
        $priceMock = "59.90";

        $this->itemAdapterMock
            ->expects($this->exactly(2))
            ->method('getPrice')
            ->will($this->onConsecutiveCalls(false, $priceMock));

        $this->itemAdapterMock
            ->expects($this->exactly(2))
            ->method('getParentItem')
            ->willReturn($this->itemAdapterMock);

        $requestModel = $this->getModel();
        $result = $requestModel->getUnitPrice();

        $this->assertSame($result, $expectedResult);
    }
}
