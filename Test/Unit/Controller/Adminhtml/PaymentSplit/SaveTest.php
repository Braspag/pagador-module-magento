<?php
namespace Webjump\BraspagPagador\Test\Unit\Controller\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit\Save;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Model\SplitManager;

class SaveTest extends \PHPUnit\Framework\TestCase
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

        $this->requestMock = $this->getMockBuilder('\Magento\Framework\App\RequestInterface')
            ->disableOriginalConstructor()
            ->setMethods(['getPost', 'getParam', 'getModuleName', 'setModuleName', 'getActionName', 'setActionName', 'setParams', 'getParams', 'getCookie', 'isSecure'])
            ->getMock();

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\Manager::class);

        $this->controller = $objectManager->getObject(
            Save::class,
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

    public function testExecuteShouldSaveWithSuccessWhenPaymentNotIsLocked()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(10))
            ->method('getParam')
            ->withConsecutive(
                ['id', null],
                ['subordinate_merchant_id', null],
                ['store_merchant_id', null],
                ['sales_quote_id', null],
                ['sales_order_id', null],
                ['mdr_applied', null],
                ['tax_applied', null],
                ['total_amount', null],
                ['store_id', null],
                ['locked', null]
            )
            ->willReturnOnConsecutiveCalls(
                '123',
                '123456',
                '123455',
                '1',
                '1',
                '2.5',
                '2.6',
                '200',
                '1'
            );

        $this->splitMock->expects($this->once())
            ->method('load')
            ->with('123')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSubordinateMerchantId')
            ->with('123456')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreMerchantId')
            ->with('123455')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesQuoteId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesOrderId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setMdrApplied')
            ->with('2.5')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTaxApplied')
            ->with('2.6')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTotalAmount')
            ->with('200')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setUpdatedAt')
            ->with((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT))
            ->willReturnSelf();

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldSaveWithSuccessWhenPaymentIsLocked()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(12))
            ->method('getParam')
            ->withConsecutive(
                ['id', null],
                ['subordinate_merchant_id', null],
                ['store_merchant_id', null],
                ['sales_quote_id', null],
                ['sales_order_id', null],
                ['mdr_applied', null],
                ['tax_applied', null],
                ['total_amount', null],
                ['store_id', null],
                ['locked', null]
            )
            ->willReturnOnConsecutiveCalls(
                '123',
                '123456',
                '123455',
                '1',
                '1',
                '2.5',
                '2.6',
                '200',
                '1',
                '0'
            );

        $this->splitMock->expects($this->exactly(2))
            ->method('getSalesOrderId')
            ->willReturn('1');

        $this->splitMock->expects($this->once())
            ->method('load')
            ->with('123')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSubordinateMerchantId')
            ->with('123456')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreMerchantId')
            ->with('123455')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesQuoteId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesOrderId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setMdrApplied')
            ->with('2.5')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTaxApplied')
            ->with('2.6')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTotalAmount')
            ->with('200')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setUpdatedAt')
            ->with((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT))
            ->willReturnSelf();

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $this->orderModel->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->orderRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->orderModel);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldNotSaveWhenInvalidPost()
    {
        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn(false);

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldNotSendLockRequestToBraspagWhenOrderNotExists()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->requestMock->expects($this->exactly(10))
            ->method('getParam')
            ->withConsecutive(
                ['id', null],
                ['subordinate_merchant_id', null],
                ['store_merchant_id', null],
                ['sales_quote_id', null],
                ['sales_order_id', null],
                ['mdr_applied', null],
                ['tax_applied', null],
                ['total_amount', null],
                ['store_id', null],
                ['locked', null]
            )
            ->willReturnOnConsecutiveCalls(
                '123',
                '123456',
                '123455',
                '1',
                '1',
                '2.5',
                '2.6',
                '200',
                '1',
                '0'
            );

        $this->splitMock->expects($this->once())
            ->method('getSalesOrderId')
            ->willReturn(null);

        $this->splitMock->expects($this->once())
            ->method('load')
            ->with('123')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSubordinateMerchantId')
            ->with('123456')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreMerchantId')
            ->with('123455')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesQuoteId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setSalesOrderId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setMdrApplied')
            ->with('2.5')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTaxApplied')
            ->with('2.6')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setTotalAmount')
            ->with('200')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setStoreId')
            ->with('1')
            ->willReturnSelf();

        $this->splitMock->expects($this->once())
            ->method('setUpdatedAt')
            ->with((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT))
            ->willReturnSelf();

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->splitMock);

        $this->messageManagerMock->expects($this->exactly(2))
            ->method('addError')
            ->willReturnOnConsecutiveCalls(
                __('Could not lock/unlock payment split. It does not exist at Braspag.')
                ,__('Could not lock/unlock payment split.')
            );

        $resultPage = $this->controller->execute();
    }

    public function testExecuteShouldReturnAnErrorMessageAfterAnException()
    {
        $post = ['id' => '123'];

        $this->requestMock->expects($this->once())
            ->method('getPost')
            ->willReturn($post);

        $this->splitFactory->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception('Error'));

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->willReturn(__('Error'));

        $resultPage = $this->controller->execute();
    }
}
