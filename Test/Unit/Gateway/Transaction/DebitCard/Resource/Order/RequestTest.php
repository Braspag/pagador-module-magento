<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Request;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    private $configMock;
    private $helperData;

    public function setUp()
    {
        $this->configMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface');
        $this->helperData = $this->createMock('\Webjump\BraspagPagador\Helper\Data');

        $this->request = new Request(
            $this->configMock,
            $this->helperData
        );
    }

    public function tearDown()
    {

    }

    public function testGetData() 
    {
        $expectedCustomerName = 'John Doe';

        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->configMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->configMock->expects($this->once())
            ->method('getPaymentReturnUrl')
            ->will($this->returnValue('http://test.com.br/'));

        $billingAddressMock = $this->createMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getBillingAddress')
            ->will($this->returnValue($billingAddressMock));

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getOrderIncrementId')
            ->will($this->returnValue('2016000001'));

        $orderAdapterMock->expects($this->once())
            ->method('getGrandTotalAmount')
            ->will($this->returnValue('157.00'));

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->setMethods([
                'getCcType',
                'getAdditionalInformation',
                'getCcNumber',
                'getCcOwner',
                'getCcExpMonth',
                'getCcExpYear',
                'getCcCid',
                'getCcSavecard',
            ])
            ->getMock();

        $infoMock->expects($this->once())
            ->method('getCcNumber')
            ->will($this->returnValue('1234123412341231'));

        $infoMock->expects($this->once())
            ->method('getCcOwner')
            ->will($this->returnValue('John Due'));

        $infoMock->expects($this->exactly(2))
            ->method('getCcType')
            ->will($this->returnValue('Cielo-Visa'));

        $infoMock->expects($this->once())
            ->method('getCcExpMonth')
            ->will($this->returnValue('05'));

        $infoMock->expects($this->once())
            ->method('getCcExpYear')
            ->will($this->returnValue('2019'));

        $infoMock->expects($this->once())
            ->method('getCcCid')
            ->will($this->returnValue('123'));

        $this->helperData->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedCustomerName);

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setPaymentData($infoMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals('2016000001', $this->request->getMerchantOrderId());
        static::assertEquals('John Doe', $this->request->getCustomerName());
        static::assertEquals('15700', $this->request->getPaymentAmount());
        static::assertEquals('Cielo', $this->request->getPaymentProvider());
        static::assertEquals('http://test.com.br/', $this->request->getPaymentReturnUrl());
        static::assertEquals('1234123412341231', $this->request->getPaymentDebitCardCardNumber());
        static::assertEquals('John Due', $this->request->getPaymentDebitCardHolder());
        static::assertEquals('05/2019', $this->request->getPaymentDebitCardExpirationDate());
        static::assertEquals('123', $this->request->getPaymentDebitCardSecurityCode());
        static::assertEquals('Visa', $this->request->getPaymentDebitCardBrand());
    }
}