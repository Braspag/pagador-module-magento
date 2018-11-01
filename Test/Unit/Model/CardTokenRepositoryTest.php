<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Webjump\BraspagPagador\Model\CardTokenRepository;
use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

class CardTokenRepositoryTest extends \PHPUnit\Framework\TestCase
{
	private $repository;

    private $cardTokenFactoryMock;

    public function setUp()
    {
        // $this->markTestIncomplete();

        $this->cardTokenFactoryMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardTokenFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->collectionMock = $this->getMockBuilder(\Webjump\BraspagPagador\Model\ResourceModel\CardToken\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenMock = $this->getMockBuilder(Webjump\BraspagPagador\Model\CardToken::class)
            ->setMethods(['getCollection', 'load', 'getId', 'setData', 'setCustomerId', 'setStoreId', 'setActive'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->cardTokenMock);

        $this->cardTokenMock->expects($this->any())
            ->method('getCollection')
            ->willReturn($this->collectionMock);

        $this->storeManagerMock = $this->createMock('Magento\Store\Model\StoreManagerInterface');

        $this->sessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->setMethods(['getCustomerId'])
            ->disableOriginalConstructor()
            ->getMock();

         $this->resourceMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\ResourceModel\CardToken')
         	->disableOriginalConstructor()
            ->setMethods(['save', 'delete'])
            ->getMock();

        $this->searchCriteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);

        $this->searchresultMock = $this->createMock('Magento\Framework\Api\SearchResultsInterface');

        $this->filterGroupsMock = $this->getMockBuilder(\Magento\Framework\Api\Search\FilterGroup::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterMock = $this->getMockBuilder(\Magento\Framework\Api\Filter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sortOrderMock = $this->getMockBuilder(\Magento\Framework\Api\SortOrder::class)
            ->disableOriginalConstructor()
            ->getMock();

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

    	$this->cardTokenMock->expects($this->once())
    	    ->method('getId')
    	    ->will($this->returnValue(1));

    	$this->cardTokenMock->expects($this->once())
    	    ->method('load')
    	    ->with($token, CardTokenInterface::TOKEN)
    	    ->will($this->returnSelf());

    	$this->cardTokenFactoryMock->expects($this->once())
    	    ->method('create')
    	    ->will($this->returnValue($this->cardTokenMock));

    	$result = $this->repository->get($token);

    	static::assertSame($this->cardTokenMock, $result);
    }

    public function testGetWithoutCardToken()
    {
        $token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

        $this->cardTokenMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(null));

        $this->cardTokenMock->expects($this->once())
            ->method('load')
            ->with($token, CardTokenInterface::TOKEN)
            ->will($this->returnValue($this->cardTokenMock));

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->cardTokenMock));

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

        $storeMock = $this->createMock('Magento\Store\Api\Data\StoreInterface');

        $storeMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(456));

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->will($this->returnValue($storeMock));

        $this->sessionMock->expects($this->once())
            ->method('getCustomerId')
            ->will($this->returnValue(123));

        $this->cardTokenMock->expects($this->once())
            ->method('setData')
            ->with($data);

        $this->cardTokenMock->expects($this->once())
            ->method('setCustomerId')
            ->with(123);

        $this->cardTokenMock->expects($this->once())
            ->method('setStoreId')
            ->with(456);

        $this->cardTokenMock->expects($this->once())
            ->method('setActive')
            ->with(true);

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->cardTokenMock));



    	$this->repository->create($data);
    }

    public function testSave()
    {
    	$token = '6e1bf77a-b28b-4660-b14f-455e2a1c95e9';

    	$cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
    		->disableOriginalConstructor()
    		->setMethods(['load', 'getId', 'getToken'])
    		->getMock();

    	$cardTokenMock->expects($this->exactly(2))
    	    ->method('getToken')
    	    ->will($this->returnValue($token));

    	$this->cardTokenFactoryMock->expects($this->once())
    	    ->method('create')
    	    ->will($this->returnValue($cardTokenMock));

         $this->repository->save($cardTokenMock);
    }

    /**
     * @expectedException \Exception
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

        $this->repository->save($cardTokenMock);

         $this->fail('should be throw exception');
    }

    public function testDelete()
    {
        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->with($cardTokenMock);

        $result = $this->repository->delete($cardTokenMock);
    }

    /**
     * @expectedException \Exception
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testDeleteWithException()
    {
        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->with($cardTokenMock)
            ->will($this->throwException(new \Exception));

        $this->repository->delete($cardTokenMock);

         $this->fail('should be throw exception');
    }

    /** @test */
    public function testgetList()
    {
        // prepare the test

        $this->searchCriteriaMock->expects($this->once())
            ->method('getFilterGroups')
            ->will($this->returnValue([$this->filterGroupsMock]));

        $this->collectionMock->expects($this->once())
            ->method('getItems')
            ->will($this->returnValue([]));

        $this->filterGroupsMock->expects($this->once())
            ->method('getFilters')
            ->will($this->returnValue([$this->filterMock]));

        $this->searchCriteriaMock->expects($this->once())
            ->method('getSortOrders')
            ->will($this->returnValue([$this->sortOrderMock]));

        // perform the changes

        $result = $this->repository->getList($this->searchCriteriaMock);

        // test the results

        static::assertEquals($this->searchresultMock, $result);
    }
}
