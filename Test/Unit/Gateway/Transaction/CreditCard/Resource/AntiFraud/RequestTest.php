<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\AntiFraud;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Request;
use PHPUnit\Framework\TestCase;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Items\RequestFactory;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\MDD\AdapterGeneralInterface;
use Magento\Framework\Session\SessionManagerInterface;

class RequestTest extends TestCase
{
    /**
     * @var AntiFraudConfigInterface
     */
    private $configMock;

    /**
     * @var RequestFactory
     */
    private $requestItemFactoryMock;

    /**
     * @var AdapterGeneralInterface
     */
    private $adapterGeneralMock;

    /**
     * @var SessionManagerInterface
     */
    private $sessionMock;

    /**
     * @var Quote
     */
    private $quoteMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(AntiFraudConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getSequence', 'getSequenceCriteria', 'userOrderIdToFingerPrint', 'getSession'
            ])
            ->getMockForAbstractClass();

        $this->requestItemFactoryMock = $this->createMock(RequestFactory::class);

        $this->adapterGeneralMock = $this->createMock(AdapterGeneralInterface::class);

        $this->sessionMock = $this->getMockBuilder(SessionManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote', 'getSessionId'])
            ->getMockForAbstractClass();

        $this->quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
    }

    /**
     * @return Request
     */
    private function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var Request $model */
        $model = $objectManager->getObject(Request::class, [
            'config' => $this->configMock,
            'requestItemFactory' => $this->requestItemFactoryMock,
            'adapterGeneral' => $this->adapterGeneralMock,
        ]);

        return $model;
    }

    public function testGetCartShippingPhone()
    {

    }

    public function testGetCartReturnsAccepted()
    {

    }

    public function testGetPaymentData()
    {

    }

    public function testSetRequestItemFactory()
    {

    }

    public function testGetCaptureOnLowRisk()
    {

    }

    public function testGetMerchantDefinedFields()
    {

    }

    public function testGetBrowserCookiesAccepted()
    {

    }

    public function testGetCartIsGift()
    {

    }

    public function testGetRequestItemFactory()
    {

    }

    public function testGetBrowserType()
    {

    }

    public function testGetCartShippingMethod()
    {

    }

    public function testGetSequence()
    {
        $valueExpected = rand();

        $this->configMock
            ->expects($this->once())
            ->method('getSequence')
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getSequence();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetCartItems()
    {

    }

    public function testGetBrowserIpAddress()
    {

    }

    public function testSetPaymentData()
    {

    }

    public function testGetVoidOnHighRisk()
    {

    }

    public function testSetOrderAdapter()
    {

    }

    public function testGetSequenceCriteria()
    {
        $valueExpected = rand();

        $this->configMock
            ->expects($this->once())
            ->method('getSequenceCriteria')
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getSequenceCriteria();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetFingerPrintId()
    {
        $userOrderIdToFingerPrint = true;
        $reservedOrderId = (string) rand();

        $this->configMock
            ->expects($this->once())
            ->method('userOrderIdToFingerPrint')
            ->willReturn($userOrderIdToFingerPrint);

        $this->configMock
            ->expects($this->once())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->sessionMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->exactly(2))
            ->method('getReservedOrderId')
            ->willReturnOnConsecutiveCalls(null, $reservedOrderId);

        $this->quoteMock
            ->expects($this->once())
            ->method('reserveOrderId')
            ->willReturnSelf();

        $this->quoteMock
            ->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $model = $this->getModel();
        $valueActual = $model->getFingerPrintId();

        $this->assertSame($reservedOrderId, $valueActual);
    }

    public function testGetFingerPrintIdShouldReturnSessionIdWhenDisabled()
    {
        $userOrderIdToFingerPrint = false;
        $sessionId = (string) rand();

        $this->configMock
            ->expects($this->once())
            ->method('userOrderIdToFingerPrint')
            ->willReturn($userOrderIdToFingerPrint);

        $this->configMock
            ->expects($this->once())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->sessionMock
            ->expects($this->once())
            ->method('getSessionId')
            ->willReturn($sessionId);


        $model = $this->getModel();
        $valueActual = $model->getFingerPrintId();

        $this->assertSame($sessionId, $valueActual);
    }

    public function testGetBrowserHostName()
    {

    }

    public function testGetCartShippingAddressee()
    {

    }

    public function testGetBrowserEmail()
    {

    }
}
