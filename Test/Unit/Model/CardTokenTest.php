<?php

namespace Webjump\BraspagPagador\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Webjump\BraspagPagador\Model\CardToken;

class CardTokenTest extends \PHPUnit_Framework_TestCase
{
	private $model;

	private $objectManagerHelper;

    public function setUp()
    {
    	$this->objectManagerHelper = new ObjectManagerHelper($this);
    }

    public function tearDown()
    {

    }

    public function testGetInstance()
    {
    	$this->model = $this->objectManagerHelper->getObject('Webjump\BraspagPagador\Model\CardToken');
        $this->model->setAlias('alias');

        static::assertInstanceOf('Webjump\BraspagPagador\Api\Data\CardTokenInterface', $this->model);
        static::assertEquals('alias', $this->model->getAlias());
    }
}
