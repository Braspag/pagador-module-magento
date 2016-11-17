<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\CardTokenRepository;
use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

class CardTokenRepositoryTest extends \PHPUnit_Framework_TestCase
{
	protected $repository;

    public function setUp()
    {
        $this->markTestIncomplete();
        
        $this->cardTokenFactoryMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardTokenFactory')
            ->setMethods(['create'])
            ->getMock();

        $this->storeManagerMock = $this->getMock('Magento\Store\Model\StoreManagerInterface');

        $this->sessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->setMethods(['getCustomerId'])
            ->disableOriginalConstructor()
            ->getMock();

         $this->resourceMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\ResourceModel\CardToken')
         	->disableOriginalConstructor()
            ->getMock();

    	$this->repository = new CardTokenRepository(
            $this->cardTokenFactoryMock,
            $this->storeManagerMock,
            $this->sessionMock,
            $this->resourceMock
    	);
    }

    public function tearDown()
    {

    }

    public function testGet()
    {
    	$token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

    	$cardToken = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
    		->disableOriginalConstructor()
    		->setMethods(['load', 'getId'])
    		->getMock();

    	$cardToken->expects($this->once())
    	    ->method('getId')
    	    ->will($this->returnValue(1));

    	$cardToken->expects($this->once())
    	    ->method('load')
    	    ->with($token, CardTokenInterface::TOKEN)
    	    ->will($this->returnValue($cardToken));

    	$this->cardTokenFactoryMock->expects($this->once())
    	    ->method('create')
    	    ->will($this->returnValue($cardToken));

    	$result = $this->repository->get($token);

    	static::assertSame($cardToken, $result);
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

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($cardTokenMock);

    	$this->repository->create($alias, $token);
    }

    public function testSave()
    {
    	$token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

    	$cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
    		->disableOriginalConstructor()
    		->setMethods(['load', 'getId', 'getToken'])
    		->getMock();

    	$cardTokenMock->expects($this->once())
    	    ->method('getId')
    	    ->will($this->returnValue(1));

    	$cardTokenMock->expects($this->exactly(2))
    	    ->method('getToken')
    	    ->will($this->returnValue($token));

    	$cardTokenMock->expects($this->once())
    	    ->method('load')
    	    ->with($token, CardTokenInterface::TOKEN)
    	    ->will($this->returnValue($cardTokenMock));

    	$this->cardTokenFactoryMock->expects($this->once())
    	    ->method('create')
    	    ->will($this->returnValue($cardTokenMock));

         $this->repository->save($cardTokenMock);
    }
}
