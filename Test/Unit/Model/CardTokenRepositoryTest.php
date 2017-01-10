<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\CardTokenRepository;
use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

class CardTokenRepositoryTest extends \PHPUnit_Framework_TestCase
{
	private $repository;

    private $cardTokenFactoryMock;

    public function setUp()
    {
        // $this->markTestIncomplete();
        
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
            ->setMethods(['save'])
            ->getMock();

        $this->searchresultMock = $this->getMock('Magento\Framework\Api\SearchResultsInterface');

    	$this->repository = new CardTokenRepository(
            $this->cardTokenFactoryMock,
            $this->storeManagerMock,
            $this->sessionMock,
            $this->resourceMock,
            $this->searchresultMock
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

    public function testGetWithoutCardToken()
    {
        $token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

        $cardToken = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getId'])
            ->getMock();

        $cardToken->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(null));

        $cardToken->expects($this->once())
            ->method('load')
            ->with($token, CardTokenInterface::TOKEN)
            ->will($this->returnValue($cardToken));

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($cardToken));

        $result = $this->repository->get($token);

        static::assertFalse($result);
    }

    public function testCreate()
    {
        $alias = '453.***.***.***.5466';
        $token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';
        $provider = 'Cielo';
        $brand = 'Visa';

        $data = [
            'alias' => $alias,
            'token' => $token,
            'provider' => $provider,
            'brand' => $brand,
        ];

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

        $cardToken = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->setMethods(['setData', 'setCustomerId', 'setStoreId', 'setActive'])
            ->getMock();

        $cardToken->expects($this->once())
            ->method('setData')
            ->with($data);

        $cardToken->expects($this->once())
            ->method('setCustomerId')
            ->with(123);

        $cardToken->expects($this->once())
            ->method('setStoreId')
            ->with(456);

        $cardToken->expects($this->once())
            ->method('setActive')
            ->with(true);

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($cardToken));



    	$this->repository->create($data);
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

    /**
     * @expectedException Exception
     */
    public function testSaveWithException()
    {
        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceMock->expects($this->once())
            ->method('save')
            ->with($cardTokenMock)
            ->will($this->throwException(new \Exception));

         try {
            $this->repository->save($cardTokenMock);             
         } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
             $this->assertEquals($e->getMessage(), 'Unable to save Card Token');
             return;
         }

         $this->fail('should be throw exception');
    }
}
