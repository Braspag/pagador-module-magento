<?php

namespace Webjump\BraspagPagador\Controller\Adminhtml\PaymentSplit;

class ExportXml extends AbstractPaymentSplit
{
    public function execute()
    {
        $this->_view->loadLayout();
        $fileName = 'paymentsplit.xml';
        $content = $this->_view->getLayout()->getBlock('braspag.paymentsplit.grid');

        return $this->_fileFactory->create(
            $fileName,
            $content->getExcelFile($fileName),
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
        );
    }
}
