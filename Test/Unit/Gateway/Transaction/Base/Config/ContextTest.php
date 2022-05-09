<?php
namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTime as CurrentDate;

class ContextTest extends \PHPUnit\Framework\TestCase
{
    private $scopeConfigMock;
    private $sessionMock;
    private $storeMock;
    private $dateTimeMock;
    private $currentDateMock;
    private $context;

    public function setUp()
    {
        // $this->markTestIncomplete();
        $this->scopeConfigMock  = $this->createMock(ScopeConfigInterface::class);
        $this->sessionMock      = $this->createMock(SessionManagerInterface::class);
        $this->storeMock        = $this->createMock(StoreManagerInterface::class);
        $this->dateTimeMock     = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->currentDateMock = $this->getMockBuilder(CurrentDate::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetScopeConfig()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock,
            $this->currentDateMock
        );
        $this->assertInstanceOf(ScopeConfigInterface::class, $this->context->getConfig());
    }

    public function testGetSession()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock,
            $this->currentDateMock
        );
        $this->assertInstanceOf(SessionManagerInterface::class, $this->context->getSession());
    }

    public function testGetStore()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock,
            $this->currentDateMock
        );

        $this->assertInstanceOf(StoreManagerInterface::class, $this->context->getStoreManager());
    }
}
