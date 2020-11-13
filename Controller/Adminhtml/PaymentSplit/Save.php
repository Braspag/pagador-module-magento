<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class Save extends AbstractPaymentSplit
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
            $id = $r->getParam('id');

            $modelFactory = $this->splitFactory->create();
            $paymentSplit = $modelFactory->load($id);

            $oldLockedValue = $paymentSplit->getLocked();

            $paymentSplit->setSubordinateMerchantId($r->getParam('subordinate_merchant_id'))
                ->setStoreMerchantId($r->getParam('store_merchant_id'))
                ->setSalesQuoteId($r->getParam('sales_quote_id'))
                ->setSalesOrderId($r->getParam('sales_order_id'))
                ->setMdrApplied($r->getParam('mdr_applied'))
                ->setTaxApplied($r->getParam('tax_applied'))
                ->setTotalAmount($r->getParam('total_amount'))
                ->setStoreId($r->getParam('store_id'))
                ->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));

            $paymentSplit->save();

            $this->messageManager->addSuccess(__('Payment Split was successfully saved'));

            try {
                if ($oldLockedValue !== $r->getParam('locked')) {

                    if (empty($paymentSplit->getSalesOrderId())) {
                        throw new \Exception(__('Could not lock/unlock payment split. It does not exist at Braspag.'));
                    }

                    $order = $this->orderRepository->get($paymentSplit->getSalesOrderId());

                    $this->splitPaymentLockCommand->execute([
                        'order' => $order,
                        'payment' => $order->getPayment(),
                        'subordinates' => [$paymentSplit->getSubordinateMerchantId()],
                        'locked' => $r->getParam('locked')
                    ]);

                    $paymentSplit->setLocked($r->getParam('locked'));
                    $paymentSplit->save();
                }

            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                $this->messageManager->addError(__('Could not lock/unlock payment split.'));
                return $resultRedirect->setPath('*/*/');
            }

            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

    }
}
