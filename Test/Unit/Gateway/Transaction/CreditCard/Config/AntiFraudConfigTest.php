<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class AntiFraudConfigTest extends \PHPUnit\Framework\TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->createMock(ContextInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->config = $objectManager->getObject(AntiFraudConfig::class, [
            'context' => $this->contextMock
        ]);
    }

    public function testGetData()
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_SEQUENCE)
            ->will($this->returnValue('AnalyseFirst'));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_SEQUENCE_CRITERIA)
            ->will($this->returnValue('Always'));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_CAPTURE_ON_LOW_RISK)
            ->will($this->returnValue(false));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_VOID_ON_HIGH_RISK)
            ->will($this->returnValue(false));


        $this->contextMock->expects($this->exactly(4))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('AnalyseFirst', $this->config->getSequence());
        static::assertEquals('Always', $this->config->getSequenceCriteria());
        static::assertFalse($this->config->getCaptureOnLowRisk());
        static::assertFalse($this->config->getVoidOnHighRisk());
    }
}
