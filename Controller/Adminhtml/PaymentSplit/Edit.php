<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class Edit extends AbstractPaymentSplit
{
    public function execute()
    {
        $resultPage = $this->_initAction();
        $id = $this->getRequest()->getParam('id');
        $resultPage->addBreadcrumb(__('Edit Payment Split'), __('Edit Payment Split'));
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Payment Split'));

        return $resultPage;
    }
}
