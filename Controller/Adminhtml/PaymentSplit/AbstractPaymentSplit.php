<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    \Webjump\BraspagPagador
 * @copyright  Copyright (c) 2015-2016 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

use \Magento\Backend\App\Action;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Model\SplitManager;

abstract class AbstractPaymentSplit extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    protected $splitFactory;

    protected $orderRepository;

    protected $splitManager;

    protected $splitPaymentTransactionPostCommand;

    protected $configCreditCardInterface;

    protected $configDebitCardInterface;

    protected $configBoletoInterface;

    /**
     * AbstractPaymentSplit constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Webjump\BraspagPagador\Model\SplitFactory $splitFactory
     * @param \Magento\Sales\Model\OrderRepository $orderRepository
     * @param Action\Context $context
     * @param SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand
     * @param SplitManager $splitManager
     * @param SplitPaymentCreditCardConfig $configCreditCardInterface
     * @param SplitPaymentDebitCardConfig $configDebitCardInterface
     * @param SplitPaymentBoletoConfig $configBoletoInterface
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Webjump\BraspagPagador\Model\SplitFactory $splitFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Backend\App\Action\Context $context,
        SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand,
        SplitManager $splitManager,
        SplitPaymentCreditCardConfig $configCreditCardInterface,
        SplitPaymentDebitCardConfig $configDebitCardInterface,
        SplitPaymentBoletoConfig $configBoletoInterface
    ) {
        $this->_registry = $registry;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        $this->splitFactory = $splitFactory;
        $this->orderRepository = $orderRepository;
        $this->splitPaymentTransactionPostCommand = $splitPaymentTransactionPostCommand;
        $this->splitManager = $splitManager;
        $this->configCreditCardInterface = $configCreditCardInterface;
        $this->configDebitCardInterface = $configDebitCardInterface;
        $this->configBoletoInterface = $configBoletoInterface;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webjump_BraspagPagador::paymentsplit');
    }

    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Webjump_BraspagPagador::paymentsplit');
        $resultPage->getConfig()->getTitle()->prepend(__('Payment Splits'));
        $resultPage->addBreadcrumb(__('Sales'), __('Sales'));
        $resultPage->addBreadcrumb(__('Braspag'), __('Braspag'));
        $resultPage->addBreadcrumb(__('Payment Splits'), __('Payment Splits'));
        return $resultPage;
    }
}
