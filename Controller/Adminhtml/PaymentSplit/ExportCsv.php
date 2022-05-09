<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class ExportCsv extends AbstractPaymentSplit
{
    public function execute()
    {
        $this->_view->loadLayout();
        $fileName = 'paymentsplit.csv';
        $content = $this->_view->getLayout()->getBlock('braspag.paymentsplit.grid');

        return $this->_fileFactory->create(
            $fileName,
            $content->getCsvFile($fileName),
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
        );
    }
}
