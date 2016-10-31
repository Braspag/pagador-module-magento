<?php
/**
 * Created by PhpStorm.
 * User: leandro
 * Date: 31/10/16
 * Time: 16:24
 */

namespace Webjump\BraspagPagador\Test\Unit\Block\Checkout\Onepage\Transaction\Billet;


use \Webjump\BraspagPagador\Block\Checkout\Onepage\Transaction\Billet\Link;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Link */
    protected $link;

    public function setUp()
    {
        $context = $this->getMockBuilder(\Magento\Framework\View\Element\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();


        $session = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->link = new Link($context, $session);
    }

    public function testGetCheckoutSession()
    {
        $this->assertInstanceOf(\Magento\Checkout\Model\Session::class, $this->link->getCheckoutSession());
    }
    
    public function testGetLastOrder()
    {
        $this->assertInstanceOf(\Magento\Sales\Model\Order::class, $this->link->getLastOrder());
    }

    public function testGetPayment()
    {
        $this->assertInstanceOf(\Magento\Sales\Model\Order\Payment::class, $this->link->getPayment());
    }

    public function testGetBilletUrl()
    {
        $this->assertTrue(method_exists($this->link, 'getBilletUrl'));
    }
}
