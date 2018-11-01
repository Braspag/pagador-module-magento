<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @billet        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Info;


use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Webjump\BraspagPagador\Model\Payment\Info\Billet;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\ResponseHandler;

class BilletTest extends \PHPUnit\Framework\TestCase
{
    const RETURN_URL = 'http://www.url-de-retorno.com.br/';

    private $payment;
    private $order;
    private $billet;

    public function testGetBilletUrl()
    {
        $this->payment = $this->getMockBuilder(OrderPaymentInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->payment->expects($this->once())
            ->method('getAdditionalInformation')
            ->with(ResponseHandler::ADDITIONAL_INFORMATION_BILLET_URL)
            ->will($this->returnValue(self::RETURN_URL));

        $this->order =  $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->order->expects($this->exactly(2))
            ->method('getPayment')
            ->will($this->returnValue($this->payment));

        $this->billet = new Billet($this->order);

        $this->assertEquals(self::RETURN_URL, $this->billet->getBilletUrl());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetBilletUrlWithoutPaymentValid()
    {
        $this->order = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->billet = new Billet($this->order);

        $this->billet->getBilletUrl();
    }
}
