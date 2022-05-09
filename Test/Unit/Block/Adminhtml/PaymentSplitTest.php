<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Adminhtml;

use Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit;

class PaymentSplitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CreditCard
     */
    private $block;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(
            PaymentSplit::class,
            []
        );

    }

    public function testConstructShouldSetTemplateWithSuccess()
    {
        $result = $this->block->_construct();
    }
}
