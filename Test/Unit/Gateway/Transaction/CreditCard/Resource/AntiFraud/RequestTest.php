<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\AntiFraud;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Request;
use PHPUnit\Framework\TestCase;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Items\RequestFactory;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\MDD\AdapterGeneralInterface;

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

    protected function setUp()
    {
        $this->configMock = $this->createMock(AntiFraudConfigInterface::class);

        $this->requestItemFactoryMock = $this->createMock(RequestFactory::class);

        $this->adapterGeneralMock = $this->createMock(AdapterGeneralInterface::class);
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
            ->method('getSequence')
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getSequenceCriteria();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetFingerPrintId()
    {

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
