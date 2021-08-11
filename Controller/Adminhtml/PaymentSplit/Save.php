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

            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

    }
}
