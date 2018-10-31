<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Test\Unit\Block\AntiFraud;


use Webjump\BraspagPagador\Api\Data\AntiFraudFingerPrintInterface;
use  Magento\Framework\View\Element\Template\Context;
use Webjump\BraspagPagador\Block\AntiFraud\FingerPrint;

class FingerPrintTest extends \PHPUnit\Framework\TestCase
{
    const ORG_ID = '1snn5n9w';
    const SESSION_ID = 'BC5D3432-527F-40C6-84BF-C549285536BE';

    private $fingerPrintInterfaceMock;
    private $contextMock;
    private $fingerPrint;

    public function setUp()
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fingerPrintInterfaceMock = $this
            ->getMockBuilder(AntiFraudFingerPrintInterface::class)
            ->getMockForAbstractClass();
    }

    public function testGetSrcParams()
    {
        $this->fingerPrintInterfaceMock->expects($this->once())
            ->method('getSessionId')
            ->will($this->returnValue(self::SESSION_ID));

        $this->fingerPrintInterfaceMock->expects($this->once())
            ->method('getOrgId')
            ->will($this->returnValue(self::ORG_ID));

        $expected = http_build_query([
            'org_id' => self::ORG_ID,
            'session_id' => self::SESSION_ID,
        ]);

        $this->fingerPrint = new FingerPrint($this->contextMock, [], $this->fingerPrintInterfaceMock);

        $this->assertEquals($expected, $this->fingerPrint->getSrcParams());
    }
}
