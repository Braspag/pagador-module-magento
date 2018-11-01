<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    private $creaditCardConfig;

    protected $objectManagerHelper;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->configMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');
        $this->installmentsconfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

        $this->request = $this->objectManagerHelper->getObject(
            Request::class,
            [
                'config' => $this->configMock,
                'installmentsConfig' => $this->installmentsconfigMock
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $this->markTestIncomplete();
        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->configMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->once())
            ->method('getSoftDescriptor')
            ->will($this->returnValue('Texto que será impresso na fatura do portador'));

        $billingAddressMock = $this->createMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

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


        $shippingAddressMock = $this->createMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

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

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getBillingAddress')
            ->will($this->returnValue($billingAddressMock));

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getShippingAddress')
            ->will($this->returnValue($shippingAddressMock));

        $orderAdapterMock->expects($this->once())
            ->method('getGrandTotalAmount')
            ->will($this->returnValue('157.00'));

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getOrderIncrementId')
            ->will($this->returnValue('2016000001'));

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

        $infoMock->expects($this->any())
            ->method('getAdditionalInformation')
            ->will($this->returnValueMap(array(
                array('cc_savecard', true),
                array('cc_installments', 3)
            )));

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setPaymentData($infoMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals('2016000001', $this->request->getMerchantOrderId());
        static::assertEquals('John Doe', $this->request->getCustomerName());
        static::assertNull($this->request->getCustomerIdentity());
        static::assertNull($this->request->getCustomerIdentityType());
        static::assertEquals('johndoe@webjump.com.br', $this->request->getCustomerEmail());
        static::assertNull($this->request->getCustomerBirthDate());
        static::assertEquals('Avenida Paulista', $this->request->getCustomerAddressStreet());
        static::assertEquals('123', $this->request->getCustomerAddressNumber());
        static::assertNull($this->request->getCustomerAddressComplement());
        static::assertEquals('01311300', $this->request->getCustomerAddressZipCode());
        static::assertEquals('Bela Vista', $this->request->getCustomerAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerAddressCity());
        static::assertEquals('SP', $this->request->getCustomerAddressState());
        static::assertEquals('BRA', $this->request->getCustomerAddressCountry());
        static::assertEquals('Avenida Paulista', $this->request->getCustomerDeliveryAddressStreet());
        static::assertEquals('123', $this->request->getCustomerDeliveryAddressNumber());
        static::assertNull($this->request->getCustomerDeliveryAddressComplement());
        static::assertEquals('01311300', $this->request->getCustomerDeliveryAddressZipCode());
        static::assertEquals('Bela Vista', $this->request->getCustomerDeliveryAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerDeliveryAddressCity());
        static::assertEquals('SP', $this->request->getCustomerDeliveryAddressState());
        static::assertEquals('BRA', $this->request->getCustomerDeliveryAddressCountry());
        static::assertEquals('15700', $this->request->getPaymentAmount());
        static::assertEquals('BRL', $this->request->getPaymentCurrency());
        static::assertEquals('BRA', $this->request->getPaymentCountry());
        static::assertEquals('Cielo', $this->request->getPaymentProvider());
        static::assertEquals(0, $this->request->getPaymentServiceTaxAmount());
        static::assertEquals(3, $this->request->getPaymentInstallments());
        static::assertEquals('ByMerchant', $this->request->getPaymentInterest());
        static::assertTrue($this->request->getPaymentCapture());
        static::assertNull($this->request->getPaymentAuthenticate());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->request->getPaymentSoftDescriptor());
        static::assertEquals('1234123412341231', $this->request->getPaymentCreditCardCardNumber());
        static::assertEquals('John Due', $this->request->getPaymentCreditCardHolder());
        static::assertEquals('05/2019', $this->request->getPaymentCreditCardExpirationDate());
        static::assertEquals('123', $this->request->getPaymentCreditCardSecurityCode());
        static::assertTrue($this->request->getPaymentCreditCardSaveCard());
        static::assertEquals('Visa', $this->request->getPaymentCreditCardBrand());
        static::assertNull($this->request->getPaymentExtraDataCollection());
        static::assertNull($this->request->getAntiFraudRequest());
    }
}
