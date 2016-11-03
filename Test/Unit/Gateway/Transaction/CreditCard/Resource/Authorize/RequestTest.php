<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    private $creaditCardConfig;

    public function setUp()
    {
        $this->config = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

    	$this->request = new Request(
            $this->config
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        static::markTestIncomplete();

        $this->config->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->config->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));            

        $billingAddressMock = $this->getMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $billingAddressMock->expects($this->exactly(2))
            ->method('getStreetLine1')
            ->will($this->returnValue('Avenida Paulista, 123'));

        $billingAddressMock->expects($this->once())
            ->method('getStreetLine2')
            ->will($this->returnValue('Bela Vista'));

        $billingAddressMock->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $billingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

        $billingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('01311300'));

        $billingAddressMock->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue('johndoe@webjump.com.br'));


        $shippingAddressMock = $this->getMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

        $shippingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $shippingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $shippingAddressMock->expects($this->exactly(2))
            ->method('getStreetLine1')
            ->will($this->returnValue('Avenida Paulista, 123'));

        $shippingAddressMock->expects($this->once())
            ->method('getStreetLine2')
            ->will($this->returnValue('Bela Vista'));

        $shippingAddressMock->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $shippingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

        $shippingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('01311300'));

        $shippingAddressMock->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue('johndoe@webjump.com.br'));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->exactly(10))
            ->method('getBillingAddress')
            ->will($this->returnValue($billingAddressMock));

        $orderAdapterMock->expects($this->exactly(10))
            ->method('getShippingAddress')
            ->will($this->returnValue($shippingAddressMock));

        $orderAdapterMock->expects($this->once())
            ->method('getGrandTotalAmount')
            ->will($this->returnValue('157.00'));

        $orderAdapterMock->expects($this->exactly(3))
            ->method('getOrderIncrementId')
            ->will($this->returnValue('2016000001'));

        $this->request->setOrderAdapter($orderAdapterMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals('2016000001', $this->request->getMerchantOrderId());
        static::assertEquals('John Doe', $this->request->getCustomerName());
        static::assertFalse($this->request->getCustomerIdentity());
        static::assertFalse($this->request->getCustomerIdentityType());
        static::assertEquals('johndoe@webjump.com.br', $this->request->getCustomerEmail());
        static::assertFalse($this->request->getCustomerBirthDate());
        static::assertEquals('Avenida Paulista', $this->request->getCustomerAddressStreet());
        static::assertEquals('123', $this->request->getCustomerAddressNumber());
        static::assertFalse($this->request->getCustomerAddressComplement());
        static::assertEquals('01311300', $this->request->getCustomerAddressZipCode());
        static::assertEquals('Bela Vista', $this->request->getCustomerAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerAddressCity());
        static::assertEquals('SP', $this->request->getCustomerAddressState());
        static::assertEquals('BR', $this->request->getCustomerAddressCountry());
        static::assertEquals('Avenida Paulista', $this->request->getCustomerDeliveryAddressStreet());
        static::assertEquals('123', $this->request->getCustomerDeliveryAddressNumber());
        static::assertFalse($this->request->getCustomerDeliveryAddressComplement());
        static::assertEquals('01311300', $this->request->getCustomerDeliveryAddressZipCode());
        static::assertEquals('Bela Vista', $this->request->getCustomerDeliveryAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerDeliveryAddressCity());
        static::assertEquals('SP', $this->request->getCustomerDeliveryAddressState());
        static::assertEquals('BR', $this->request->getCustomerDeliveryAddressCountry());
        static::assertEquals('15700', $this->request->getPaymentAmount());
        static::assertEquals('BRL', $this->request->getPaymentCurrency());
        static::assertEquals('BRA', $this->request->getPaymentCountry());
        static::assertEquals('Simulado', $this->request->getPaymentProvider());
        static::assertEquals('1000', $this->request->getPaymentServiceTaxAmount());
        static::assertEquals('3', $this->request->getPaymentInstallments());
        static::assertEquals('ByMerchant', $this->request->getPaymentInterest());
        static::assertTrue($this->request->getPaymentCapture());
        static::assertTrue($this->request->getPaymentAuthenticate());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->request->getPaymentSoftDescriptor());
        static::assertEquals('1234123412341231', $this->request->getPaymentCreditCardCardNumber());
        static::assertEquals('Comprador T Test ', $this->request->getPaymentCreditCardHolder());
        static::assertEquals('05/2019', $this->request->getPaymentCreditCardExpirationDate());
        static::assertEquals('123', $this->request->getPaymentCreditCardSecurityCode());
        static::assertTrue($this->request->getPaymentCreditCardSaveCard());
        static::assertEquals('Visa', $this->request->getPaymentCreditCardBrand());
        static::assertFalse($this->request->getPaymentExtraDataCollection());
        static::assertFalse($this->request->getAntiFraudRequest());
    }
}
