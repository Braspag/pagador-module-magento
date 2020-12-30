<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @boleto        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Info;


use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Webjump\BraspagPagador\Model\Payment\Info\Boleto;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Response\BaseHandler as ResponseHandler;

class BoletoTest extends \PHPUnit\Framework\TestCase
{
    const RETURN_URL = 'http://www.url-de-retorno.com.br/';

    private $payment;
    private $order;
    private $boleto;

    public function testGetBoletoUrl()
    {
        $this->payment = $this->getMockBuilder(OrderPaymentInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->payment->expects($this->once())
            ->method('getAdditionalInformation')
            ->with(ResponseHandler::ADDITIONAL_INFORMATION_BOLETO_URL)
            ->will($this->returnValue(self::RETURN_URL));

        $this->order =  $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->order->expects($this->exactly(2))
            ->method('getPayment')
            ->will($this->returnValue($this->payment));

        $this->boleto = new Boleto($this->order);

        $this->assertEquals(self::RETURN_URL, $this->boleto->getBoletoUrl());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetBoletoUrlWithoutPaymentValid()
    {
        $this->order = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->boleto = new Boleto($this->order);

        $this->boleto->getBoletoUrl();
    }
}
