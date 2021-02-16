<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

use Webjump\BraspagPagador\Model\Source\PaymentSplitType;

class SendTransactionalPost extends AbstractPaymentSplit
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->getRequest()->getPost()) {
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $r = $this->getRequest();
            $orderId = $r->getParam('order_id');

            $order = $this->orderRepository->get($orderId);

            $paymentMethod = $order->getPayment()->getMethod();

            $splitPaymentTransactionPostCommand = null;

            if ($paymentMethod === \Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider::CODE
                && ($this->configCreditCardInterface->isPaymentSplitActive()
                    && $this->configCreditCardInterface->getPaymentSplitType() === PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
                )
            ) {
                $splitPaymentTransactionPostCommand = $this->splitPaymentTransactionPostCommand;
            }

            if ($paymentMethod === \Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider::CODE
                && ($this->configDebitCardInterface->isPaymentSplitActive()
                    && $this->configDebitCardInterface->getPaymentSplitType() === PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
                )
            ) {
                $splitPaymentTransactionPostCommand = $this->splitPaymentTransactionPostCommand;
            }

            if ($paymentMethod === \Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider::CODE
                && ($this->configBoletoInterface->isPaymentSplitActive()
                    && $this->configBoletoInterface->getPaymentSplitType() === PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
                )
            ) {
                $splitPaymentTransactionPostCommand = $this->splitPaymentTransactionPostCommand;
            }

            if ($splitPaymentTransactionPostCommand == null) {
                throw new \Exception('Invalid Payment Method');
            }

            try {
                $splitPaymentTransactionPostCommand->execute(['order' => $order, 'payment' => $order->getPayment()]);
            } catch (\Exception $e) {
                $this->messageManager->addError('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage());
                $order->addCommentToStatusHistory('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage(), false);
                $order->save();
            }

            return $resultRedirect->setPath('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);

        } catch (\Exception $e) {
            $this->messageManager->addError('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage());
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);
        }

    }
}
