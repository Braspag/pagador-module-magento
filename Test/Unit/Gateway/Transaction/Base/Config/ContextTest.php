<?php
namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Context;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    private $scopeConfigMock;
    private $sessionMock;
    private $storeMock;
    private $dateTimeMock;
    private $context;

    public function setUp()
    {
        $this->scopeConfigMock  = $this->getMock(ScopeInterface::class);
        $this->sessionMock      = $this->getMock(SessionManagerInterface::class);
        $this->storeMock        = $this->getMock(StoreManagerInterface::class);
        $this->dateTimeMock     = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetScopeConfig()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock
        );
        $this->assertInstanceOf(ScopeInterface::class, $this->context->getConfig());
    }

    public function testGetSession()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock
        );
        $this->assertInstanceOf(SessionManagerInterface::class, $this->context->getSession());
    }

    public function testGetStore()
    {
        $this->context = new Context(
            $this->scopeConfigMock,
            $this->sessionMock,
            $this->storeMock,
            $this->dateTimeMock
        );

        $this->assertInstanceOf(StoreManagerInterface::class, $this->context->getStoreManager());
    }
}
