<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\PaymentSplit;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Api\SplitDataProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\PaymentSplit\Request;
use PHPUnit\Framework\TestCase;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config as PaymentSplitConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\Items\RequestFactory;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\MDD\AdapterGeneralInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\AddressAdapterInterface;
use Magento\Payment\Model\InfoInterface;
use \Magento\Sales\Model\Order\Item;
use Webjump\BraspagPagador\Model\OAuth2TokenManager;
use Webjump\BraspagPagador\Model\PaymentSplit\FingerPrint\FingerPrint;

class RequestTest extends TestCase
{
    private $configMock;

    private $sessionMock;

    private $dataProviderMock;

    private $oAuth2TokenManagerMock;

    private $quoteMock;

    private $orderMock;

    private $paymentMock;

    private $paymentTransactionMock;

    protected function setUp()
    {
        $this->dataProviderMock = $this->createMock(\Webjump\BraspagPagador\Model\SplitDataProvider::class);
        $this->oAuth2TokenManagerMock = $this->createMock(\Webjump\BraspagPagador\Model\OAuth2TokenManager::class);
        $this->quoteMock = $this->createMock(\Magento\Quote\Model\Quote::class);
        $this->orderMock = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->paymentMock = $this->createMock(\Magento\Sales\Model\Order\Payment::class);
        $this->paymentTransactionMock = $this->createMock(\Magento\Sales\Model\Order\Payment\Transaction::class);

        $this->sessionMock = $this->getMockBuilder('\Magento\Framework\Session\SessionManagerInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getQuote',
                'start',
                'writeClose',
                'isSessionExists',
                'getSessionId',
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
                'isValidForPath'])
            ->getMock();

        $this->configMock = $this->getMockBuilder(PaymentSplitConfig::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'isPaymentSplitActive',
                'getPaymentSplitDefaultMrd',
                'getPaymentSplitDefaultFee',
                'getIsTestEnvironment',
                'getMerchantId'
            ])
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
            'session' => $this->sessionMock,
            'dataProvider' => $this->dataProviderMock,
            'oAuth2TokenManager' => $this->oAuth2TokenManagerMock
        ]);

        return $model;
    }

    public function testGetDataProvider()
    {
        $requestModel = $this->getModel();
        $result = $requestModel->getDataProvider();

        $this->assertSame($result, $this->dataProviderMock);
    }

    public function testGetOAuth2TokenManager()
    {
        $requestModel = $this->getModel();
        $result = $requestModel->getOAuth2TokenManager();

        $this->assertSame($result, $this->oAuth2TokenManagerMock);
    }

    public function testPrepareSplitsShouldPrepareSplitsWithSuccessWhenNotEmptyQuote()
    {
        $this->configMock->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultMrd')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultFee')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->willReturn('123456789');

        $this->dataProviderMock->expects($this->once())
            ->method('setQuote')
            ->with($this->quoteMock)
            ->willReturnSelf();

        $requestModel = $this->getModel();
        $requestModel->setConfig($this->configMock);
        $requestModel->setQuote($this->quoteMock);
        $result = $requestModel->prepareSplits();

        $this->assertSame($result, $requestModel);
    }

    public function testPrepareSplitsShouldPrepareSplitsWithSuccessWhenNotEmptyOrder()
    {
        $this->configMock->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultMrd')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultFee')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->willReturn('123456789');

        $this->dataProviderMock->expects($this->once())
            ->method('setOrder')
            ->with($this->orderMock)
            ->willReturnSelf();

        $this->dataProviderMock->expects($this->once())
            ->method('getData')
            ->with('123456789', 3.20, 3.20)
            ->willReturn([]);

//        $this->sessionMock->expects($this->once())
//            ->method('getQuote')
//            ->willReturn($this->quoteMock);

        $requestModel = $this->getModel();
        $requestModel->setConfig($this->configMock);
        $requestModel->setOrder($this->orderMock);
        $result = $requestModel->prepareSplits();

        $this->assertSame($result, $requestModel);
    }

    public function testPrepareSplitsShouldReturnEmptyArrayWhenPaymentSplitIsInactive()
    {
        $this->configMock->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(false);

        $requestModel = $this->getModel();
        $requestModel->setConfig($this->configMock);
        $requestModel->setQuote($this->quoteMock);
        $result = $requestModel->prepareSplits();

        $this->assertSame($result, []);
    }

    public function testGetSplits()
    {
        $this->configMock->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultMrd')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitDefaultFee')
            ->willReturn(3.20);

        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->willReturn('123456789');

        $this->dataProviderMock->expects($this->once())
            ->method('setOrder')
            ->with($this->orderMock)
            ->willReturnSelf();

        $this->dataProviderMock->expects($this->once())
            ->method('getData')
            ->with('123456789', 3.20, 3.20)
            ->willReturn([]);

//        $this->sessionMock->expects($this->once())
//            ->method('getQuote')
//            ->willReturn($this->quoteMock);

        $requestModel = $this->getModel();
        $requestModel->setConfig($this->configMock);
        $requestModel->setOrder($this->orderMock);
        $requestModel->prepareSplits();

        $result = $requestModel->getSplits();

        $this->assertSame($result, []);
    }

    public function testIsTestEnvironment()
    {
        $this->configMock->expects($this->once())
            ->method('getIsTestEnvironment')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $requestModel->setConfig($this->configMock);

        $result = $requestModel->isTestEnvironment();

        $this->assertTrue($result);
    }

    public function testGetOrderTransactionIdShouldReturnTransactionId()
    {
        $this->paymentTransactionMock->expects($this->once())
            ->method('getTxnId')
            ->willReturn('123456789');

        $this->paymentMock->expects($this->once())
            ->method('getAuthorizationTransaction')
            ->willReturn($this->paymentTransactionMock);

        $this->orderMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->paymentMock);

        $requestModel = $this->getModel();
        $requestModel->setOrder($this->orderMock);

        $result = $requestModel->getOrderTransactionId();

        $this->assertEquals($result, '123456789');
    }

    public function testGetOrderTransactionIdShouldReturnNullWhenInvalidOrder()
    {
        $requestModel = $this->getModel();

        $result = $requestModel->getOrderTransactionId();

        $this->assertEquals($result, null);
    }

    public function testSetStoreId()
    {
        $requestModel = $this->getModel();

        $result = $requestModel->setStoreId(1);

        $this->assertEquals($result, null);
    }

    public function testGetStoreId()
    {
        $requestModel = $this->getModel();

        $requestModel->setStoreId(1);
        $result = $requestModel->getStoreId();

        $this->assertEquals($result, 1);
    }

    public function testGetAccessToken()
    {
        $this->oAuth2TokenManagerMock->expects($this->once())
            ->method('getToken')
            ->willReturn('123456789');

        $requestModel = $this->getModel();

        $result = $requestModel->getAccessToken();

        $this->assertEquals($result, '123456789');
    }

}
