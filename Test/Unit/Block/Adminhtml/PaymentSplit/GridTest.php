<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Grid;
use Magento\Backend\Helper\Data as HelperData;
use Magento\Framework\View\LayoutFactory;
use Webjump\BraspagPagador\Helper\Data as BraspagHelperData;

class GridTest extends \PHPUnit\Framework\TestCase
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
    private $helperData;

    private $viewLayoutFactory;

    private $storeOptions;

    private $context;

    private $backendHelper;

    private $splitRepository;

    private $sourceYesno;

    private $filesystemMock;

    private $dataObjectMock;

    private $urlBuilderMock;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(\Magento\Backend\Block\Template\Context::class);

        $this->filesystemMock = $this->createMock(\Magento\Framework\Filesystem::class);

         $this->contextMock->expects($this->once())
             ->method('getFilesystem')
             ->willReturn($this->filesystemMock);

        $this->viewLayoutFactory = $this->createMock(LayoutFactory::class);
        $this->storeOptions = $this->createMock(\Magento\Store\Model\System\Store::class);

        $this->helperData = $this->createMock(\Webjump\BraspagPagador\Helper\Data::class);

        $this->backendHelper = $this->createMock(\Magento\Backend\Helper\Data::class);

        $this->sourceYesno = $this->createMock(\Magento\Config\Model\Config\Source\Yesno::class);

        $this->splitRepositoryMock = $this->createMock(\Webjump\BraspagPagador\Api\SplitRepositoryInterface::class);

        $this->urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);

        $this->contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->block = $objectManager->getObject(
            Grid::class,
            [
                'helperData' => $this->helperData,
                'viewLayoutFactory' => $this->viewLayoutFactory,
                'storeOptions' => $this->storeOptions,
                'context' => $this->contextMock,
                'backendHelper' => $this->backendHelper,
                'splitRepository' => $this->splitRepositoryMock,
                'sourceYesno' => $this->sourceYesno,
                'data' => []
            ]
        );
    }

    public function testGetRowUrlShouldReturnRowUrlWithSuccess()
    {
        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getId'])
            ->getMock();

        $this->dataObjectMock->expects($this->once())
            ->method('getId')
            ->willReturn('123');

        $this->urlBuilderMock->expects($this->exactly(1))
            ->method('getUrl')
            ->with(
                '*/*/edit'
            )
            ->willReturn(
                '*/*/edit'
            );

        $result = $this->block->getRowUrl($this->dataObjectMock);

        $this->assertEquals("*/*/edit", $result);
    }

    public function testGetGridUrlShouldReturnRowUrlWithSuccess()
    {
        $this->urlBuilderMock->expects($this->exactly(1))
            ->method('getUrl')
            ->with(
                '*/*/grid'
            )
            ->willReturn(
                '*/*/grid'
            );

        $result = $this->block->getGridUrl();

        $this->assertEquals("*/*/grid", $result);
    }
}
