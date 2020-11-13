<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Avs;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Avs\Request;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\AddressAdapterInterface;
use Magento\Payment\Model\InfoInterface;

class RequestTest extends TestCase
{
    /**
     * @var AvsConfigInterface
     */
    private $sessionMock;

    /**
     * @var RequestFactory
     */
    private $model;

    /**
     * @var OrderAdapterInterface
     */
    private $orderAdapterMock;

    /**
     * @var
     */
    private $billingAddressMock;

    /**
     * @var
     */
    private $shippingAddressMock;

    /**
     * @var InfoInterface
     */
    private $infoInterfaceMock;

    protected function setUp()
    {
        $this->orderAdapterMock = $this->createMock(OrderAdapterInterface::class);
        $this->billingAddressMock = $this->createMock(AddressAdapterInterface::class);
        $this->shippingAddressMock = $this->createMock(AddressAdapterInterface::class);
        $this->infoInterfaceMock = $this->createMock(InfoInterface::class);

        $this->sessionMock = $this->getMockBuilder( 'Magento\Framework\Session\SessionManagerInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getQuote',
                'getSessionId',
                'start',
                'writeClose',
                'isSessionExists',
                'getName',
                'setName',
                'destroy',
                'clearStorage',
                'getCookieDomain',
                'getCookiePath',
                'getCookieLifetime',
                'setSessionId',
                'regenerateId',
                'expireSessionCookie',
                'getSessionIdForHost',
                'isValidForHost',
                'isValidForPath'
            ])
            ->getMock();

        $this->quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerTaxvat'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var Request $model */
        $this->requestModel = $objectManager->getObject(Request::class, [
            'session' => $this->sessionMock
        ]);
    }

    public function testGetData()
    {
        $this->quoteMock->expects($this->exactly(1))
            ->method('getCustomerTaxvat')
            ->willReturn('12345678912');

        $this->sessionMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->billingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->willReturn('12345678');

        $this->billingAddressMock->expects($this->once())
            ->method('getStreetLine1')
            ->willReturn('rua x');

        $this->shippingAddressMock->expects($this->once())
            ->method('getStreetLine1')
            ->willReturn('rua x,123');

        $this->shippingAddressMock->expects($this->once())
            ->method('getStreetLine2')
            ->willReturn('Centro');

        $this->orderAdapterMock->expects($this->once())
            ->method('getBillingAddress')
            ->willReturn($this->billingAddressMock);

        $this->orderAdapterMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->shippingAddressMock);

        $this->requestModel->setOrderAdapter($this->orderAdapterMock);
        $this->requestModel->setPaymentData($this->infoInterfaceMock);
        $this->assertEquals('12345678912',  $this->requestModel->getCpf());
        $this->assertEquals('12345678',  $this->requestModel->getZipCode());
        $this->assertEquals('rua x',  $this->requestModel->getStreet());
        $this->assertEquals('123',  $this->requestModel->getNumber());
        $this->assertEquals(null,  $this->requestModel->getComplement());
        $this->assertEquals('Centro',  $this->requestModel->getDistrict());
    }
}
