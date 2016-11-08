<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Info;


use Webjump\BraspagPagador\Model\Payment\Info\Billet;
use Webjump\BraspagPagador\Model\Payment\Info\BilletFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\ObjectManagerInterface;

class BilletFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $billetFactory;
    private $order;
    private $objectManager;

    public function setUp()
    {
        $this->order = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager =  $this->getMockBuilder(ObjectManagerInterface::class)
            ->getMockForAbstractClass();

        $this->billetFactory = new BilletFactory($this->objectManager, $this->order);
    }

    public function testCreate()
    {
        $this->markTestIncomplete();
        $this->assertInstanceOf(Billet::class, $this->billetFactory->create());
    }
}
