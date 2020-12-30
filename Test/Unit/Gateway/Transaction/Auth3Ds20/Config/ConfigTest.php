<?php

namespace Tests\Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

/**
 * Class ConfigTest.
 *
 * @covers \Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config\Config
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Config
     */
    protected $config;
    protected $context;
    protected $contextAdmin;
    protected $scopeConfig;
    protected $appState;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(ContextInterface::class);
        $this->contextAdmin = $this->createMock(ContextInterface::class);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->appState = $this->createMock(State::class);

        $this->context->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->scopeConfig);

        /** @todo Correctly instantiate tested object to use it. */
        $this->config = new Config(
            $this->context,
            $this->contextAdmin,
            $this->scopeConfig,
            $this->appState,
            []
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->config);
    }

    public function testGetAuth3Ds20ClientId(): void
    {
        $this->config->getAuth3Ds20ClientId();
    }

    public function testGetAuth3Ds20ClientSecret(): void
    {
        $this->config->getAuth3Ds20ClientSecret();
    }
}
