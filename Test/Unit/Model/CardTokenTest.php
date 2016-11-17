<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Webjump\BraspagPagador\Model\CardToken;
use Webjump\BraspagPagador\Api\Data\CardTokenInterface;

class CardTokenTest extends \PHPUnit_Framework_TestCase
{
	private $model;

	private $objectManagerHelper;

    public function setUp()
    {
    	$this->objectManagerHelper = new ObjectManagerHelper($this);
    	$this->model = $this->objectManagerHelper->getObject(CardToken::class);
        $this->model->setData($this->getCardTokenData());
    }

    public function tearDown()
    {

    }

    public function testGetInstance()
    {
        $data = $this->getCardTokenData();
        
        static::assertEquals($data[CardTokenInterface::ALIAS], $this->model->getAlias());
        static::assertEquals($data[CardTokenInterface::TOKEN], $this->model->getToken());
        static::assertEquals($data[CardTokenInterface::CUSTOMER_ID], $this->model->getCustomerId());
        static::assertEquals($data[CardTokenInterface::STORE_ID], $this->model->getStoreId());
        static::assertEquals($data[CardTokenInterface::BRAND], $this->model->getBrand());
        static::assertTrue($this->model->isActive());
    }

    public function getCardTokenData()
    {
        return [
            CardTokenInterface::ALIAS => '453.***.***.***.5466',
            CardTokenInterface::TOKEN => '6e1bf77a-b28b-4660-b14f-455e2a1c95e9',
            CardTokenInterface::CUSTOMER_ID => 1,
            CardTokenInterface::STORE_ID => 2,
            CardTokenInterface::ACTIVE => 1,
            CardTokenInterface::BRAND => 'brand',
        ];
    }
}
