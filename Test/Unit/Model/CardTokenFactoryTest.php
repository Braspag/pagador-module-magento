<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\CardTokenFactory;

class CardTokenFactoryTest extends \PHPUnit_Framework_TestCase
{
	private $factory;

    private $storeManagerMock;

    private $sessionMock;

    public function setUp()
    {
        $this->objectManagerMock = $this->getMockBuilder('Magento\Framework\ObjectManagerInterface')
            ->getMockForAbstractClass();

        $this->storeManagerMock = $this->getMock('Magento\Store\Model\StoreManagerInterface');

        $this->sessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->setMethods(['getCustomerId'])
            ->disableOriginalConstructor()
            ->getMock();

    	$this->factory = new CardTokenFactory(
            $this->objectManagerMock,
            $this->storeManagerMock,
            $this->sessionMock            
        );
    }

    public function tearDown()
    {

    }

    public function testCreate()
    {
        $alias = '453.***.***.***.5466';
        $token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

        $storeMock = $this->getMock('Magento\Store\Api\Data\StoreInterface');

        $storeMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(456));

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->will($this->returnValue($storeMock));

        $this->sessionMock->expects($this->once())
            ->method('getCustomerId')
            ->will($this->returnValue(123));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();
        
        $cardTokenMock->expects($this->once())
            ->method('setAlias')
            ->with($alias)
            ->will($this->returnValue($cardTokenMock));

        $cardTokenMock->expects($this->once())
            ->method('setToken')
            ->with($token)
            ->will($this->returnValue($cardTokenMock));

        $cardTokenMock->expects($this->once())
            ->method('setCustomerId')
            ->with(123)
            ->will($this->returnValue($cardTokenMock));

        $cardTokenMock->expects($this->once())
            ->method('setStoreId')
            ->with(456)
            ->will($this->returnValue($cardTokenMock));

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Webjump\BraspagPagador\Model\CardToken')
            ->willReturn($cardTokenMock);

    	$this->factory->create($alias, $token);
    }
}
