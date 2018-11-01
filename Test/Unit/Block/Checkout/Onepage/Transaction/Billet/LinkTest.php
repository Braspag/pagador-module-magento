<?php
/**
 * Created by PhpStorm.
 * User: leandro
 * Date: 31/10/16
 * Time: 16:24
 */

namespace Webjump\BraspagPagador\Test\Unit\Block\Checkout\Onepage\Transaction\Billet;


use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\ResponseHandler;
use Webjump\BraspagPagador\Block\Checkout\Onepage\Transaction\Billet\Link;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\Data\OrderInterface as Order;
use Magento\Sales\Api\Data\OrderPaymentInterface as Payment;
use Magento\Checkout\Model\Session  as Session;
use Magento\Framework\View\Element\Template\Context;

class LinkTest extends \PHPUnit\Framework\TestCase
{
    const RETURN_URL = 'http://www.url-de-retorno.com.br/';
    private $context;

    public function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetBilletUrl()
    {
        $payment = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $payment->expects($this->once())
            ->method('getAdditionalInformation')
            ->with(ResponseHandler::ADDITIONAL_INFORMATION_BILLET_URL)
            ->will($this->returnValue(self::RETURN_URL));

        $order = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $order->expects($this->exactly(2))
            ->method('getPayment')
            ->will($this->returnValue($payment));

        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $session->expects($this->exactly(4))
            ->method('getLastRealOrder')
            ->will($this->returnValue($order));

        $link = new Link($this->context, $session);

        $this->assertEquals(self::RETURN_URL, $link->getBilletUrl());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetBilletUrlWithoutPaymentValid()
    {
        $order = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $session->expects($this->exactly(2))
            ->method('getLastRealOrder')
            ->will($this->returnValue($order));

        $link = new Link($this->context, $session);
        $link->getBilletUrl();
    }
}
