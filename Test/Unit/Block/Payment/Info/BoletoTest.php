<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Payment\Info;

use Magento\Framework\View\Element\Template\Context;
use Webjump\BraspagPagador\Block\Payment\Info\Boleto;
use Webjump\BraspagPagador\Model\Payment\Info\BoletoFactoryInterface;

class BoletoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CreditCard
     */
    private $block;

    /**
     * @var Context
     */
    private $contextMock;

    /**
     * @var InstallmentsInterface
     */
    private $boletoFactory;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(Context::class);
        $this->boletoFactory = $this->getMockBuilder(BoletoFactoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->block = $objectManager->getObject(
            Boleto::class,
            [
                'context' => $this->contextMock,
                'boletoFactory' => $this->boletoFactory,
                'data' => []
            ]
        );

    }

    public function testConstructShouldSetTemplateWithSuccess()
    {
        $result = $this->block->_construct();
    }
}
