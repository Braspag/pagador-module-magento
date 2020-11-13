<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class Index extends AbstractPaymentSplit
{
    public function execute()
    {
        $resultPage = $this->_initAction();
        return $resultPage;
    }
}
