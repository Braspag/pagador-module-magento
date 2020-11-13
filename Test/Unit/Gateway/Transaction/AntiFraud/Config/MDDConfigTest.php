<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\AntiFraud\Config;

use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config\MDDConfig;
use PHPUnit\Framework\TestCase;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class MDDConfigTest extends TestCase
{
    /**
     * @var ContextInterface
     */
    private $contextMock;

    /**
     * @var Session
     */
    private $sessionMock;

    /**
     * @var Quote
     */
    private $quoteMock;

    /**
     * @var CustomerInterface
     */
    private $customerMock;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfigMock;

    protected function setUp()
    {
        $this->contextMock = $this->createMock(ContextInterface::class);

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();

        $this->quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
    }

    /**
     * @return MDDConfig
     */
    private function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var MDDConfig $model */
        $model = $objectManager->getObject(MDDConfig::class, [
            'context' => $this->contextMock
        ]);

        return $model;
    }

    public function testGetVerticalSegment()
    {
        $valueExpected = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_VERTICAL_SEGMENT)
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getVerticalSegment();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetStoreCode()
    {
        $valueExpected = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_STORE_CODE)
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getStoreCode();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetQuote()
    {
        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->sessionMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $model = $this->getModel();
        $quote = $model->getQuote();

        $this->assertSame($this->quoteMock, $quote);
    }

    public function testGetCustomer()
    {
        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->sessionMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getCustomer')
            ->willReturn($this->customerMock);

        $model = $this->getModel();
        $customer = $model->getCustomer();

        $this->assertSame($this->customerMock, $customer);
    }

    public function testGetConfirmEmailAddress()
    {
        $emailAddress = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_CUSTOMER_CREATE_NEED_CONFIRM)
            ->willReturn($emailAddress);

        $model = $this->getModel();
        $email = $model->getConfirmEmailAddress();

        $this->assertSame($emailAddress, $email);
    }

    public function testGetCategoryAttributeCode()
    {
        $valueExpected = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_CATEGORY_ATTRIBUTE_CODE)
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getCategoryAttributeCode();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetFetchSelfShippingMethod()
    {
        $valueExpected = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_FETCH_SELF_SHIPPING_METHOD)
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getFetchSelfShippingMethod();

        $this->assertSame($valueExpected, $valueActual);
    }

    public function testGetStoreIdentity()
    {
        $valueExpected = rand();

        $this->contextMock
            ->expects($this->atLeastOnce())
            ->method('getConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(MDDConfig::XML_PATH_STORE_IDENTITY)
            ->willReturn($valueExpected);

        $model = $this->getModel();
        $valueActual = $model->getStoreIdentity();

        $this->assertSame($valueExpected, $valueActual);
    }
}
