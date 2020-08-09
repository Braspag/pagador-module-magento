<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Test\Unit\Model\AntiFraud\FingerPrint;


use Webjump\BraspagPagador\Model\AntiFraud\FingerPrint\FingerPrint;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;

class FingerPrintTest extends \PHPUnit\Framework\TestCase
{
    const SRC_PNG_IMG_URL = 'https://h.online-metrix.net/fp/clear.png';
    const SRC_JS_URL = 'https://h.online-metrix.net/fp/clear.png';
    const SRC_FLASH_URL = 'https://h.online-metrix.net/fp/clear.png';

    const ORG_ID = '1snn5n9w';

    const SESSION_ID = '123456789987654321';
    const MERCHANT_ID = 'BC5D3432-527F-40C6-84BF-C549285536BE';

    /** @var FingerPrint */
    private $fingerPrint;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $scopeFingerPrintMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $sessionMock;

    private $customerRepository;
    private $quoteFactory;
    private $quote;

    protected function setUp()
    {
        $this->scopeFingerPrintMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->getMockForAbstractClass();

        $this->customerRepository = $this->getMockBuilder('Magento\Customer\Api\CustomerRepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteFactory = $this->getMockBuilder('Magento\Quote\Model\QuoteFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->quote = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getCustomerId'])
            ->getMock();

        $this->sessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->setMethods(['getQuote', 'getSessionId', 'getId'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetSrcPngImageUrl()
    {
        $this->scopeFingerPrintMock->expects($this->once())
            ->method('getValue')
            ->with(FingerPrint::XML_SRC_PNG_IMAGE_URL)
            ->will($this->returnValue(self::SRC_PNG_IMG_URL));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $this->assertEquals(self::SRC_PNG_IMG_URL, $this->fingerPrint->getSrcPngImageUrl());
    }

    public function testGetSrcJsUrl()
    {
        $this->scopeFingerPrintMock->expects($this->once())
            ->method('getValue')
            ->with(FingerPrint::XML_SRC_JS_URL)
            ->will($this->returnValue(self::SRC_JS_URL));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $this->assertEquals(self::SRC_JS_URL, $this->fingerPrint->getSrcJsUrl());
    }

    public function testGetSrcFlashUrl()
    {
        $this->scopeFingerPrintMock->expects($this->once())
            ->method('getValue')
            ->with(FingerPrint::XML_SRC_FLASH_URL)
            ->will($this->returnValue(self::SRC_FLASH_URL));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $this->assertEquals(self::SRC_FLASH_URL, $this->fingerPrint->getSrcFlashUrl());
    }

    public function testGetOrgId()
    {
        $this->scopeFingerPrintMock->expects($this->once())
            ->method('getValue')
            ->with(FingerPrint::XML_ORG_ID)
            ->will($this->returnValue(self::ORG_ID));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $this->assertEquals(self::ORG_ID, $this->fingerPrint->getOrgId());
    }

    public function testGetSessionId()
    {
        $this->scopeFingerPrintMock->expects($this->at(0))
            ->method('getValue')
            ->with(\Webjump\BraspagPagador\Model\AntiFraud\FingerPrint\FingerPrint::XML_ORDER_ID_TO_FINGERPRINT)
            ->will($this->returnValue(false));

        $this->scopeFingerPrintMock->expects($this->at(1))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/fingerprint/merchant_id')
            ->will($this->returnValue(self::MERCHANT_ID));

        $this->quote->expects($this->at(2))
            ->method('getCustomerId')
            ->will($this->returnValue(1));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $sessionIdExpected = self::MERCHANT_ID.'d41d8cd98f00b204e9800998ecf8427e';

        $this->assertEquals($sessionIdExpected,
            $this->fingerPrint->getSessionId(false, $this->quote,null)
        );
    }

    public function testGetSessionIdFromQuoteId()
    {
        $this->scopeFingerPrintMock->expects($this->at(0))
            ->method('getValue')
            ->with(\Webjump\BraspagPagador\Model\AntiFraud\FingerPrint\FingerPrint::XML_ORDER_ID_TO_FINGERPRINT)
            ->will($this->returnValue(true));

        $this->scopeFingerPrintMock->expects($this->at(1))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/fingerprint/merchant_id')
            ->will($this->returnValue(self::MERCHANT_ID));

        $quoteMock = $this->getMockBuilder('Magento\Quote\Model\Quote')
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock->expects($this->at(0))
            ->method('getReservedOrderId')
            ->will($this->returnValue(0));

        $quoteMock->expects($this->once())
            ->method('reserveOrderId');

        $quoteMock->expects($this->at(2))
            ->method('getReservedOrderId')
            ->will($this->returnValue(self::SESSION_ID));

        $this->sessionMock->expects($this->once())
            ->method('getQuote')
            ->will($this->returnValue($quoteMock));

        $this->fingerPrint = new FingerPrint(
            $this->scopeFingerPrintMock,
            $this->sessionMock,
            $this->customerRepository,
            $this->quoteFactory
        );

        $sessionIdExpected = self::MERCHANT_ID;

        $this->assertEquals($sessionIdExpected, $this->fingerPrint->getSessionId());
    }

}
