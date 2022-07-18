<?php

namespace Braspag\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class Grid extends AbstractPaymentSplit
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}