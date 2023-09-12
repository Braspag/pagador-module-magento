<?php

namespace Braspag\BraspagPagador\Plugin;

use Magento\Framework\Registry;
use Braspag\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Braspag\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Braspag\BraspagPagador\Model\Source\PaymentSplitType;
use Braspag\BraspagPagador\Model\SplitManager;

class AdminhtmlOrderView
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    protected $splitManager;
    protected $splitPaymentTransactionPostCommand;
    protected $configCreditCardInterface;
    protected $configDebitCardInterface;
    protected $configBoletoInterface;
    protected $_authorization;

    /**
     * AdminhtmlOrderView constructor.
     * @param Registry $frameworkRegistry
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand
     * @param SplitManager $splitManager
     * @param SplitPaymentCreditCardConfig $configCreditCardInterface
     * @param SplitPaymentDebitCardConfig $configDebitCardInterface
     * @param SplitPaymentBoletoConfig $configBoletoInterface
     */
    public function __construct(
        Registry $frameworkRegistry,
        \Magento\Framework\AuthorizationInterface $authorization,
        SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand,
        SplitManager $splitManager,
        SplitPaymentCreditCardConfig $configCreditCardInterface,
        SplitPaymentDebitCardConfig $configDebitCardInterface,
        SplitPaymentBoletoConfig $configBoletoInterface
    ) {
        $this->_coreRegistry = $frameworkRegistry;
        $this->_authorization = $authorization;
        $this->splitPaymentTransactionPostCommand = $splitPaymentTransactionPostCommand;
        $this->splitManager = $splitManager;
        $this->configCreditCardInterface = $configCreditCardInterface;
        $this->configDebitCardInterface = $configDebitCardInterface;
        $this->configBoletoInterface = $configBoletoInterface;
    }

    public function beforeSetLayout(
        \Magento\Sales\Block\Adminhtml\Order\View $subject,
        \Magento\Framework\View\LayoutInterface $layout
    ) {

        $paymentMethod = $this->_coreRegistry->registry('sales_order')->getPayment()->getMethod();
        if (
            $paymentMethod === \Braspag\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider::CODE
            && (!$this->configCreditCardInterface->isPaymentSplitActive()
                || $this->configCreditCardInterface->getPaymentSplitType() !== PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
            )
        ) {
            return null;
        }

        if (
            $paymentMethod === \Braspag\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider::CODE
            && (!$this->configDebitCardInterface->isPaymentSplitActive()
                || $this->configDebitCardInterface->getPaymentSplitType() !== PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
            )
        ) {
            return null;
        }

        if (
            $paymentMethod === \Braspag\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider::CODE
            && (!$this->configBoletoInterface->isPaymentSplitActive()
                || $this->configBoletoInterface->getPaymentSplitType() !== PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
            )
        ) {
            return null;
        }

        if (
            $this->_coreRegistry->registry('sales_order')
            && $this->_authorization
                ->isAllowed('braspag_BraspagPagador::action_paymentsplit_posttransactionsend')
        ) {
            $subject->addButton('send_paymentSplitPostTransaction', [
                'label'   => __('Send Payment Split'),
                'class'   => 'paymentsplit_sendttransactionalpost',
                'onclick' => 'setLocation(\'' . $subject->getUrl('braspag/paymentsplit/sendTransactionalPost') . '\')'
            ]);
            return null;
        }
    }
}