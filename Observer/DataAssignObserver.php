<?php

namespace Webjump\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;

class DataAssignObserver extends AbstractDataAssignObserver
{
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $data = $this->readDataArgument($observer);
        $paymentInfo = $method->getInfoInstance();

        if ($data->getDataByKey('cc_owner') !== null) {
            $paymentInfo->setAdditionalInformation('cc_owner', $data->getDataByKey('cc_owner'));
        }

        if ($data->getDataByKey('cc_installments') !== null) {
            $paymentInfo->setAdditionalInformation('cc_installments', $data->getDataByKey('cc_installments'));
        }

        return $this;
    }
}
