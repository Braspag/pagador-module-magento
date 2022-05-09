<?php
namespace Webjump\BraspagPagador\Test\Unit\Controller\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit\SendTransactionalPost;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Model\SplitManager;

class SendTransactionalPostTest extends \PHPUnit\Framework\TestCase
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
    private $authorizationMock;

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

        $this->requestMock = $this->getMockBuilder('\Magento\Framework\App\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods(['getPost', 'getParam', 'getModuleName', 'setModuleName', 'getActionName', 'setActionName', 'setParams', 'getParams', 'getCookie', 'isSecure'])
            ->getMock();

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\Manager::class);

        $this->authorizationMock = $this->createMock(\Magento\Framework\Authorization::class);

        $this->controller = $objectManager->getObject(
            SendTransactionalPost::class,
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

        $objectManager->setBackwardCompatibleProperty($this->controller, '_request', $this->requestMock);
        $objectManager->setBackwardCompatibleProperty($this->controller, 'resultRedirectFactory', $this->resultRedirectFactory);
        $objectManager->setBackwardCompatibleProperty($this->controller, 'messageManager', $this->messageManagerMock);
        $objectManager->setBackwardCompatibleProperty($this->controller, '_authorization', $this->authorizationMock);
    }

    public function testExecuteShouldSendTransactionalPostsWhenCreditCardPaymentSplitIsEnabled()
    {

        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['order_id', null]
            )
            ->willReturnOnConsecutiveCalls(
                '1'
            );

        $this->orderPayment->expects($this->once())
            ->method('getMethod')
            ->willReturn(\Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider::CODE);

        $this->orderModel->expects($this->exactly(2))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $this->configCreditCardInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configCreditCardInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldSendTransactionalPostsWhenDebitCardPaymentSplitIsEnabled()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['order_id', null]
            )
            ->willReturnOnConsecutiveCalls(
                '1'
            );

        $this->orderPayment->expects($this->once())
            ->method('getMethod')
            ->willReturn(\Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider::CODE);

        $this->orderModel->expects($this->exactly(2))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $this->configDebitCardInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configDebitCardInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldSendTransactionalPostsWhenBoletoPaymentSplitIsEnabled()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['order_id', null]
            )
            ->willReturnOnConsecutiveCalls(
                '1'
            );

        $this->orderPayment->expects($this->once())
            ->method('getMethod')
            ->willReturn(\Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider::CODE);

        $this->orderModel->expects($this->exactly(2))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $this->configBoletoInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnWithoutAnyActionAfterInvalidRequest()
    {
        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn(false);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnAnErrorMessageWhenTransactionPostIsInactive()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['order_id', null]
            )
            ->willReturnOnConsecutiveCalls(
                '1'
            );

        $this->orderModel->expects($this->exactly(1))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnAnErrorMessageWhenTransactionPostException()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['order_id', null]
            )
            ->willReturnOnConsecutiveCalls(
                '1'
            );

        $this->orderPayment->expects($this->once())
            ->method('getMethod')
            ->willReturn(\Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider::CODE);

        $this->orderModel->expects($this->exactly(2))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $this->configBoletoInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $this->splitPaymentTransactionPostCommand->expects($this->once())
            ->method('execute')
            ->with(['order' => $this->orderModel, 'payment' => $this->orderPayment])
            ->willThrowException(new \Exception('Error'));

        $resultPage = $this->controller->execute();
    }
}
