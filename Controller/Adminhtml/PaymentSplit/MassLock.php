<?php

namespace Braspag\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class MassLock extends AbstractPaymentSplit
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
                                __('Could not lock payment split. It does not exist at Braspag.')
                            );
                        }

                        $order = $this->orderRepository->get($paymentSplit->getSalesOrderId());

                        $this->splitPaymentLockCommand->execute([
                            'order' => $order,
                            'payment' => $order->getPayment(),
                            'subordinates' => [$paymentSplit->getSubordinateMerchantId()],
                            'locked' => 1
                        ]);

                        $paymentSplit->setLocked(1);
                        $paymentSplit->save();

                        $successedLocks++;
                    } catch (\Exception $e) {
                        $this->messageManager->addError(
                            __('Could not lock Payment Split') . " " . $paymentSplitId
                        );
                    }
                }
                $this->messageManager->addSuccess(
                    __('Total of %1 record(s) were successfully locked', $successedLocks)
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
