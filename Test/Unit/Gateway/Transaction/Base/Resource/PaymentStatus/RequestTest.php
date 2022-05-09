<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Resource\PaymentStatus;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\Request;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;

    protected $objectManagerHelper;
    protected $context;
    protected $contextAdmin;
    protected $scopeConfig;
    protected $appState;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->context = $this->createMock(ContextInterface::class);
        $this->contextAdmin = $this->createMock(ContextInterface::class);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->appState = $this->createMock(State::class);

        $this->request = $this->objectManagerHelper->getObject(
            Request::class, [
                'context' => $this->context,
                'contextAdmin' => $this->contextAdmin,
                'scopeConfig' => $this->scopeConfig,
                'appState' => $this->appState,
                []
            ]
        );

        $this->objectManagerHelper->setBackwardCompatibleProperty($this->request, 'paymentId', '123');
    }

    public function tearDown()
    {

    }

    public function testIsTestEnvironment()
    {
        $this->context->expects($this->exactly(1))
            ->method('getConfig')
            ->willReturn($this->scopeConfig);

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(ConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            ->willReturn(false);

        static::assertFalse($this->request->isTestEnvironment());
    }

    public function testSetPaymentId()
    {
        $this->request->setPaymentId('123');
    }

    public function testGetPaymentId()
    {
        $this->request->setPaymentId('123');
        self::assertEquals(123, $this->request->getPaymentId());
    }

    public function testSetAdditionalRequest()
    {
        $this->request->setAdditionalRequest('123');
    }

    public function testGetAdditionalRequest()
    {
        $this->request->setAdditionalRequest('123');
        self::assertEquals(123, $this->request->getAdditionalRequest());
    }

    public function testGetRequestDataBody()
    {
        self::assertEquals([], $this->request->getRequestDataBody());
    }

    public function testSetStoreId()
    {
        $this->request->setStoreId('1');
    }

    public function testGetStoreId()
    {
        $this->request->setStoreId('1');
        self::assertEquals(1, $this->request->getStoreId());
    }
}
