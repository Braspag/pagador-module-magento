<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class MassUnlock extends AbstractPaymentSplit
{
    public function execute()
    {
        $paymentSplits = $this->getRequest()->getParam('paymentsplit');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!is_array($paymentSplits)) {
            $this->messageManager->addError(__('Please select payment split(s)'));
        } else {
            try {
                $modelFactory = $this->splitFactory->create();

                $successedLocks = 0;
                foreach ($paymentSplits as $paymentSplitId) {

                    try {
                        $paymentSplit = $modelFactory->load($paymentSplitId);

                        if (empty($paymentSplit->getSalesOrderId())) {
                            throw new \Exception(
                                __('Could not unlock payment split. It does not exist at Braspag.'));
                        }

                        $order = $this->orderRepository->get($paymentSplit->getSalesOrderId());

                        $this->splitPaymentLockCommand->execute([
                            'order' => $order,
                            'payment' => $order->getPayment(),
                            'subordinates' => [$paymentSplit->getSubordinateMerchantId()],
                            'locked' => 0
                        ]);

                        $paymentSplit->setLocked(0);
                        $paymentSplit->save();

                        $successedLocks++;

                    } catch (\Exception $e) {
                        $this->messageManager->addError(
                            __('Could not unlock Payment Split')." ".$paymentSplitId);
                    }
                }
                $this->messageManager->addSuccess(
                    __('Total of %1 record(s) were successfully unlocked', $successedLocks)
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
