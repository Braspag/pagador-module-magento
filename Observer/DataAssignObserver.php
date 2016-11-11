<?php

namespace Webjump\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Framework\DataObject;

/**
 * Credit Card Data Assign
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $info = $method->getInfoInstance();
        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        $info->addData([
            'cc_type' => $additionalData->getCcType(),
            'cc_owner' => $additionalData->getCcOwner(),
            'cc_number' => $additionalData->getCcNumber(),
            'cc_cid' => $additionalData->getCcCid(),
            'cc_exp_month' => $additionalData->getCcExpMonth(),
            'cc_exp_year' => $additionalData->getCcExpYear(),
            'cc_installments' => $additionalData->getCcInstallments(),
            'cc_savecard' => (boolean) $additionalData->getCcSavecard(),
        ]);

        return $this;
    }
}