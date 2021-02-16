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


use Webjump\BraspagPagador\Model\Payment\Info\Boleto;
use Webjump\BraspagPagador\Model\Payment\Info\BoletoFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\ObjectManagerInterface;

class BoletoFactoryTest extends \PHPUnit\Framework\TestCase
{
    private $boletoFactory;
    private $orderMock;

    public function setUp()
    {
        $this->orderMock = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreate()
    {
        $this->boletoFactory = new BoletoFactory();

        $this->assertInstanceOf(Boleto::class, $this->boletoFactory->create($this->orderMock));
    }
}
