<?php

namespace Braspag\BraspagPagador\Test\Unit\Controller\Adminhtml\PaymentSplit;

use Braspag\BraspagPagador\Controller\Adminhtml\PaymentSplit\ExportCsv;
use Braspag\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Braspag\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Braspag\BraspagPagador\Model\SplitManager;

class ExportCsvTest extends \PHPUnit\Framework\TestCase
{
    private $controller;
    private $registry;
    private $resultForwardFactory;
    private $resultPageFactory;
    private $resultPageMock;
    private $resultPageConfigMock;
    private $resultPageTitleMock;
    private $resultRedirectFactory;
    private $fileFactory;
    private $splitFactory;
    private $orderRepository;
    private $context;
    private $splitPaymentTransactionPostCommand;
    private $splitManager;
    private $configCreditCardInterface;
    private $configDebitCardInterface;
    private $configBoletoInterface;
    private $requestMock;
    private $viewMock;
    private $viewLayoutMock;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->registry = $this->createMock(\Magento\Framework\Registry::class);

        $this->resultPageMock = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);

        $this->resultPageConfigMock = $this->createMock(\Magento\Framework\View\Page\Config::class);

        $this->resultPageTitleMock = $this->createMock(\Magento\Framework\View\Page\Title::class);

        $this->resultForwardFactory = $this->getMockBuilder('\Magento\Backend\Model\View\Result\ForwardFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultPageFactory = $this->getMockBuilder('\Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultRedirectFactory = $this->getMockBuilder('\Magento\Framework\Controller\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->fileFactory = $this->getMockBuilder('\Magento\Framework\App\Response\Http\FileFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->splitFactory = $this->getMockBuilder('\Braspag\BraspagPagador\Model\SplitFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->orderRepository = $this->createMock(\Magento\Sales\Model\OrderRepository::class);

        $this->context = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->splitPaymentTransactionPostCommand = $this->createMock(SplitPaymentTransactionPostCommand::class);

        $this->splitManager = $this->createMock(SplitManager::class);

        $this->configCreditCardInterface = $this->createMock(SplitPaymentCreditCardConfig::class);

        $this->configDebitCardInterface = $this->createMock(SplitPaymentDebitCardConfig::class);

        $this->configBoletoInterface = $this->createMock(SplitPaymentBoletoConfig::class);

        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);

        $this->blockMock = $this->createMock(\Braspag\BraspagPagador\Block\Adminhtml\PaymentSplit\Grid::class);

        $this->viewLayoutMock = $this->createMock(\Magento\Framework\View\Layout::class);
        $this->viewLayoutMock->expects($this->once())
            ->method('getBlock')
            ->willReturn($this->blockMock);

        $this->viewMock = $this->createMock(\Magento\Framework\App\View::class);
        $this->viewMock->expects($this->once())
            ->method('loadLayout')
            ->willReturnSelf();

        $this->viewMock->expects($this->once())
            ->method('getLayout')
            ->willReturn($this->viewLayoutMock);

        $this->controller = $objectManager->getObject(
            ExportCsv::class,
            [
                'registry' => $this->registry,
                'resultForwardFactory' => $this->resultForwardFactory,
                'resultPageFactory' => $this->resultPageFactory,
                'resultRedirectFactory' => $this->resultRedirectFactory,
                'fileFactory' => $this->fileFactory,
                'splitFactory' => $this->splitFactory,
                'orderRepository' => $this->orderRepository,
                'context' => $this->context,
                'splitPaymentTransactionPostCommand' => $this->splitPaymentTransactionPostCommand,
                'splitManager' => $this->splitManager,
                'configCreditCardInterface' => $this->configCreditCardInterface,
                'configDebitCardInterface' => $this->configDebitCardInterface,
                'configBoletoInterface' => $this->configBoletoInterface,
            ]
        );

        $objectManager->setBackwardCompatibleProperty($this->controller, '_view', $this->viewMock);
    }

    public function testExecuteShouldExecuteWithSuccess()
    {
        $resultPage = $this->controller->execute();
    }
}