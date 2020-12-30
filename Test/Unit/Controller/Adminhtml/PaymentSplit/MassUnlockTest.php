<?php
namespace Webjump\BraspagPagador\Test\Unit\Controller\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit\MassUnlock;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Model\SplitManager;

class MassUnlockTest extends \PHPUnit\Framework\TestCase
{
    private $controller;
    private $registry;
    private $resultForwardFactory;
    private $resultPageFactory;
    private $resultPageMock;
    private $resultPageConfigMock;
    private $resultPageTitleMock;
    private $resultRedirectFactory;
    private $resultRedirectMock;
    private $fileFactory;
    private $splitFactory;
    private $splitMock;
    private $splitPaymentLockCommand;
    private $orderRepository;
    private $orderModel;
    private $orderPayment;
    private $context;
    private $splitPaymentTransactionPostCommand;
    private $splitManager;
    private $configCreditCardInterface;
    private $configDebitCardInterface;
    private $configBoletoInterface;
    private $requestMock;
    private $messageManagerMock;

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

        $this->resultRedirectMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);

        $this->resultRedirectFactory = $this->getMockBuilder('\Magento\Framework\Controller\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultRedirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->fileFactory = $this->getMockBuilder('\Magento\Framework\App\Response\Http\FileFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->splitMock = $this->createMock(\Webjump\BraspagPagador\Model\Split::class);

        $this->splitFactory = $this->getMockBuilder('\Webjump\BraspagPagador\Model\SplitFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->splitPaymentLockCommand = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\LockCommand::class);

        $this->orderRepository = $this->createMock(\Magento\Sales\Model\OrderRepository::class);

        $this->orderModel = $this->createMock(\Magento\Sales\Model\Order::class);

        $this->orderPayment = $this->createMock(\Magento\Sales\Model\Order\Payment::class);

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

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\Manager::class);

        $this->controller = $objectManager->getObject(
            MassUnlock::class,
            [
                'registry' => $this->registry,
                'resultForwardFactory' => $this->resultForwardFactory,
                'resultPageFactory' => $this->resultPageFactory,
                'resultRedirectFactory' => $this->resultRedirectFactory,
                'fileFactory' => $this->fileFactory,
                'splitFactory' => $this->splitFactory,
                'splitPaymentLockCommand' => $this->splitPaymentLockCommand,
                'orderRepository' => $this->orderRepository,
                'context' => $this->context,
                'splitPaymentTransactionPostCommand' => $this->splitPaymentTransactionPostCommand,
                'splitManager' => $this->splitManager,
                'configCreditCardInterface' => $this->configCreditCardInterface,
                'configDebitCardInterface' => $this->configDebitCardInterface,
                'configBoletoInterface' => $this->configBoletoInterface,
            ]
        );

        $objectManager->setBackwardCompatibleProperty($this->controller, '_request', $this->requestMock);
        $objectManager->setBackwardCompatibleProperty($this->controller, 'resultRedirectFactory', $this->resultRedirectFactory);
        $objectManager->setBackwardCompatibleProperty($this->controller, 'messageManager', $this->messageManagerMock);
    }

    public function testExecuteShouldReturnSuccessMessage()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('paymentsplit')
            ->willReturn(['123']);

        $this->splitMock->expects($this->once())
            ->method('load')
            ->with('123')
            ->willReturnSelf();

        $this->splitMock->expects($this->exactly(2))
            ->method('getSalesOrderId')
            ->willReturn('123');

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->willReturn(__('Total of %1 record(s) were successfully unlocked', 1));

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnErrorMessageWhenInvalidPaymentSplits()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('paymentsplit')
            ->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->willReturn(__('Please select payment split(s)'));

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnErrorMessageAfterException()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('paymentsplit')
            ->willReturn(['123']);

        $this->splitMock->expects($this->once())
            ->method('load')
            ->with('123')
            ->willReturnSelf();

        $this->splitMock->expects($this->exactly(1))
            ->method('getSalesOrderId')
            ->willReturn(null);

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->willReturn(__('Could not lock Payment Split')." 123");

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnGeneralErrorMessageAfterException()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('paymentsplit')
            ->willReturn(['123']);

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception('Error'));

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->willReturn(__('Error'));

        $resultPage = $this->controller->execute();
    }
}
