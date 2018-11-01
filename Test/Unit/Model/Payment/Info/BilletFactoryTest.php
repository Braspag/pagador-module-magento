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

class BilletFactoryTest extends \PHPUnit\Framework\TestCase
{
    private $billetFactory;
    private $orderMock;

    public function setUp()
    {
        $this->orderMock = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreate()
    {
        $this->billetFactory = new BilletFactory();

        $this->assertInstanceOf(Billet::class, $this->billetFactory->create($this->orderMock));
    }
}
