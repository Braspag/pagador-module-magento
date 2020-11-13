<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\AntiFraud;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\AntiFraud\Request;
use PHPUnit\Framework\TestCase;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config\ConfigInterface as AntiFraudConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\Items\RequestFactory;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\MDD\AdapterGeneralInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\AddressAdapterInterface;
use Magento\Payment\Model\InfoInterface;
use \Magento\Sales\Model\Order\Item;
use Webjump\BraspagPagador\Model\AntiFraud\FingerPrint\FingerPrint;

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

    /**
     * @var Item
     */
    private $itemMock;

    private $helperDataMock;

    private $fingerPrintMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(AntiFraudConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getSequence', 'getSequenceCriteria', 'userOrderIdToFingerPrint', 'getSession'
            ])
            ->getMockForAbstractClass();

        $this->fingerPrintMock = $this->getMockBuilder(FingerPrint::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSessionId'])
            ->getMock();

        $this->orderAdapterMock = $this->createMock(OrderAdapterInterface::class);
        $this->billingAddressMock = $this->createMock(AddressAdapterInterface::class);
        $this->shippingAddressMock = $this->createMock(AddressAdapterInterface::class);
        $this->infoInterfaceMock = $this->createMock(InfoInterface::class);
        $this->itemMock = $this->createMock(Item::class);
        $this->requestItemFactoryMock = $this->createMock(RequestFactory::class);

        $this->adapterGeneralMock = $this->createMock(AdapterGeneralInterface::class);

        $this->helperDataMock = $this->getMockBuilder('\Webjump\BraspagPagador\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(['removeSpecialCharacters'])
            ->getMock();

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
            'helperData' => $this->helperDataMock,
            'fingerPrint' => $this->fingerPrintMock
        ]);

        return $model;
    }

    public function testGetCartShippingPhone()
    {
        $phone = "1145454545";
        $expectedResult = "55".$phone;


        $this->orderAdapterMock->expects($this->exactly(2))
            ->method('getShippingAddress')
            ->willReturn($this->billingAddressMock);

        $this->billingAddressMock->expects($this->once())
            ->method('getTelephone')
            ->willReturn($phone);

        $requestModel = $this->getModel();
        $requestModel->setOrderAdapter($this->orderAdapterMock);
        $result = $requestModel->getCartShippingPhone();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCaptureOnLowRisk()
    {
        $expectedResult = true;

        $this->configMock
            ->expects($this->once())
            ->method('getCaptureOnLowRisk')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $result = $requestModel->getCaptureOnLowRisk();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetMerchantDefinedFields()
    {
        $expectedResult = $this->adapterGeneralMock;

        $this->adapterGeneralMock
            ->expects($this->once())
            ->method('setPaymentData')
            ->with($this->infoInterfaceMock)
            ->willReturnSelf();

        $this->adapterGeneralMock
            ->expects($this->once())
            ->method('setOrderAdapter')
            ->with($this->orderAdapterMock)
            ->willReturnSelf();

        $requestModel = $this->getModel();

        $requestModel->setOrderAdapter($this->orderAdapterMock);
        $requestModel->setPaymentData($this->infoInterfaceMock);
        $result = $requestModel->getMerchantDefinedFields();
        $this->assertSame($result, $expectedResult);
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
        $expectedResult = [$this->itemMock];

        $items = [$this->itemMock, $this->itemMock];

        $this->orderAdapterMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $this->itemMock
            ->expects($this->exactly(5))
            ->method('getProductType')
            ->will($this->onConsecutiveCalls("simple", "grouped", "virtual", "downloadable"));

        $this->itemMock
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(false);

        $this->itemMock
            ->expects($this->once())
            ->method('getParentItemId')
            ->willReturn(false);

        $this->requestItemFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->itemMock)
            ->willReturn($this->itemMock);

        $requestModel = $this->getModel();

        $requestModel->setOrderAdapter($this->orderAdapterMock);
        $requestModel->setRequestItemFactory($this->requestItemFactoryMock);
        $result = $requestModel->getCartItems();
        $this->assertSame($result, $expectedResult);
    }

    public function testGetBrowserIpAddress()
    {

        $expectedResult = "123.456.789.123";

        $this->orderAdapterMock
            ->expects($this->once())
            ->method('getRemoteIp')
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();

        $requestModel->setOrderAdapter($this->orderAdapterMock);
        $result = $requestModel->getBrowserIpAddress();
        $this->assertSame($result, $expectedResult);
    }

    public function testGetVoidOnHighRisk()
    {

        $expectedResult = true;

        $this->configMock
            ->expects($this->once())
            ->method('getVoidOnHighRisk')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $result = $requestModel->getVoidOnHighRisk();

        $this->assertSame($result, $expectedResult);
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

        $model = $this->getModel();

        $this->orderAdapterMock->expects($this->exactly(1))
            ->method('getCustomerId')
            ->willReturn(1);

        $model->setOrderAdapter($this->orderAdapterMock);

        $this->fingerPrintMock->expects($this->exactly(1))
            ->method('getSessionId')
            ->willReturn($reservedOrderId);

        $valueActual = $model->getFingerPrintId();

        $this->assertSame($reservedOrderId, $valueActual);
    }

    public function testGetFingerPrintIdShouldReturnSessionIdWhenDisabled()
    {
        $userOrderIdToFingerPrint = false;
        $sessionId = (string) rand();

        $model = $this->getModel();
        $model->setOrderAdapter($this->orderAdapterMock);

        $this->fingerPrintMock->expects($this->exactly(1))
            ->method('getSessionId')
            ->willReturn($sessionId);

        $valueActual = $model->getFingerPrintId();

        $this->assertSame($sessionId, $valueActual);
    }


    public function testGetCartShippingAddressee()
    {
        $firstName = " Jôhn";
        $lastName=" Doe. - ";
        $expectedResult = "John Doe";

        $this->orderAdapterMock
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->shippingAddressMock);

        $this->shippingAddressMock
            ->expects($this->once())
            ->method('getFirstName')
            ->willReturn($firstName);

        $this->shippingAddressMock
            ->expects($this->once())
            ->method('getLastName')
            ->willReturn($lastName);

        $this->helperDataMock->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();

        $requestModel->setOrderAdapter($this->orderAdapterMock);
        $result = $requestModel->getCartShippingAddressee();
        $this->assertSame($result, $expectedResult);
    }
}
