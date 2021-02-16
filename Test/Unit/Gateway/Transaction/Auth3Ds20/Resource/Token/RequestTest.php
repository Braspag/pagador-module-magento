<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\Request;
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

        $this->context->expects($this->exactly(2))
            ->method('getConfig')
            ->willReturn($this->scopeConfig);

        $this->request = $this->objectManagerHelper->getObject(
            Request::class, [
                'context' => $this->context,
                'contextAdmin' => $this->contextAdmin,
                'scopeConfig' => $this->scopeConfig,
                'appState' => $this->appState,
                []
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        static::assertEquals('Og==', $this->request->getAccessToken());
    }
}
