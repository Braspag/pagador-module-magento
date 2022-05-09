<?php
namespace Webjump\BraspagPagador\Test\Unit\Controller\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit\Edit;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Model\SplitManager;

class EditTest extends \PHPUnit\Framework\TestCase
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

        $this->splitFactory = $this->getMockBuilder('\Webjump\BraspagPagador\Model\SplitFactory')
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

        $this->controller = $objectManager->getObject(
            Edit::class,
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

        $this->resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('Webjump_BraspagPagador::paymentsplit')
            ->willReturnSelf();

        $this->resultPageConfigMock->expects($this->exactly(2))
            ->method('getTitle')
            ->willReturn($this->resultPageTitleMock);

        $this->resultPageMock->expects($this->exactly(2))
            ->method('getConfig')
            ->willReturn($this->resultPageConfigMock);

        $this->resultPageMock->expects($this->exactly(4))
            ->method('addBreadcrumb')
            ->withConsecutive([__('Sales'), __('Sales')],[__('Braspag'), __('Braspag')],[__('Payment Splits'), __('Payment Splits')], [__('Edit Payment Split'), __('Edit Payment Split')])
            ->willReturnSelf();

        $this->resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultPageMock);

        $objectManager->setBackwardCompatibleProperty($this->controller, '_request', $this->requestMock);
    }

    public function testExecuteShouldExecuteWithSuccess()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn('123');

        $resultPage = $this->controller->execute();
    }
}
