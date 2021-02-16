<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Adminhtml\PaymentSplit;

use Magento\Backend\Block\Widget\Context;
use Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit;
use \Magento\Framework\Registry;

class EditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CreditCard
     */
    private $block;

    /**
     * @var Context
     */
    private $contextMock;

    /**
     * @var InstallmentsInterface
     */
    private $registryMock;

    private $dropshipHelperMock;

    private $splitRepositoryMock;

    private $splitMock;

    private $urlBuilderMock;

    private $dataObjectMock;

    private $buttonListMock;

    private $requestMock;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(Context::class);

        $this->urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->urlBuilderMock->expects($this->exactly(3))
            ->method('getUrl')
            ->withConsecutive(
                ['*/*/'],
                ['*/*/delete'],
                ['*/*/save']

            )
            ->willReturnOnConsecutiveCalls(
                '*/*/',
                '*/*/delete',
                '*/*/save'
            );

        $this->contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->buttonListMock = $this->createMock(\Magento\Backend\Block\Widget\Button\ButtonList::class);
        $this->contextMock->expects($this->once())
            ->method('getButtonList')
            ->willReturn($this->buttonListMock);

        $this->escaperMock = $this->createMock(\Magento\Framework\Escaper::class);
        $this->contextMock->expects($this->once())
            ->method('getEscaper')
            ->willReturn($this->escaperMock);

        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);

        $this->requestMock->expects($this->exactly(5))
            ->method('getParam')
            ->with('id')
            ->willReturn('123');

        $this->contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->dropshipHelperMock = $this->createMock(\Webjump\BraspagPagador\Helper\Data::class);
        $this->registryMock = $this->createMock(Registry::class);

        $this->splitMock = $this->createMock(\Webjump\BraspagPagador\Model\Split::class);
        $this->splitMock->expects($this->once())
            ->method('load')
            ->willReturnSelf();

        $this->splitRepositoryMock = $this->createMock(\Webjump\BraspagPagador\Api\SplitRepositoryInterface::class);
        $this->splitRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $this->block = $objectManager->getObject(
            Edit::class,
            [
                'dropshipHelper' => $this->dropshipHelperMock,
                'registry' => $this->registryMock,
                'context' => $this->contextMock,
                'splitRepository' => $this->splitRepositoryMock,
                'data' => []
            ]
        );
    }

    public function testGetHeaderTextShouldReturnSplitTitleWithSuccess()
    {
        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getId', 'getShippingCode'])
            ->getMock();

        $this->dataObjectMock->expects($this->once())
            ->method('getId')
            ->willReturn('123');

        $this->dataObjectMock->expects($this->once())
            ->method('getShippingCode')
            ->willReturn('123');

        $this->registryMock->expects($this->exactly(3))
            ->method('registry')
            ->with('paymentsplit_data')
            ->willReturn($this->dataObjectMock);

        $result = $this->block->getHeaderText();

        $expectedResult = __("Edit Payment Split '%1'", null);

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetHeaderTextShouldReturnSplitTitleEmpty()
    {
        $this->registryMock->expects($this->exactly(1))
            ->method('registry')
            ->with('paymentsplit_data')
            ->willReturn(false);

        $result = $this->block->getHeaderText();

        $expectedResult = "";

        $this->assertEquals($expectedResult, $result);
    }
}
