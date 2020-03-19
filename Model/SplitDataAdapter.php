<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Api\SplitDataAdapterInterface;

class SplitDataAdapter implements SplitDataAdapterInterface
{
    /**
     * @var
     */
    protected $objectFactory;

    /**
     * PaymentSplitManager constructor.
     * @param SplitRepositoryInterface $splitRepository
     * @param SplitItemRepositoryInterface $splitItemRepository
     * @param ManagerInterface $eventManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory
    ) {
        $this->objectFactory = $objectFactory;
    }

    /**
     * @param array $splitPaymentData
     * @param null $merchantId
     * @return \Magento\Framework\DataObject
     */
    public function adapt(array $splitPaymentData, $merchantId = null)
    {
        $dataSplitPayments = $this->objectFactory->create();
        $splitPayments = [];
        foreach ($splitPaymentData as $subordinateId => $subordinate) {

            $subordinateDataObject = $this->objectFactory->create();
            $subordinateFaresDataObject = $this->objectFactory->create();

            $subordinateFaresData = [
                "mdr" => floatval(isset($subordinate['Fares']['Mdr']) ?
                    $subordinate['Fares']['Mdr'] : $subordinate['fares']['mdr']
                ),
                "fee" => floatval(isset($subordinate['Fares']['Fee']) ?
                    $subordinate['Fares']['Fee'] : $subordinate['fares']['fee']
                )
            ];

            $subordinateFaresDataObject->addData($subordinateFaresData);

            $subordinateMerchantId = isset($subordinate['SubordinateMerchantId']) ?
                $subordinate['SubordinateMerchantId'] : $subordinateId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['Amount']) ?
                    $subordinate['Amount'] : $subordinate['amount'],
                "fares" => $subordinateFaresDataObject
            ];

            if (isset($subordinate['items'])) {
                $subordinateData['items'] = $subordinate['items'];
            }

            $subordinateData['splits'] = [];

            if (isset($subordinate['Splits'])) {
                $subordinateData['splits'] = $this->adaptSplits($subordinate['Splits']);
            }

            $subordinateDataObject->addData($subordinateData);

            $splitPayments['subordinates'][] = $subordinateDataObject;
            $splitPayments['store_merchant_id'] = strtolower($merchantId);
        }

        $dataSplitPayments->addData($splitPayments);

        return $dataSplitPayments;
    }

    /**
     * @param $subordinateSplits
     * @return array
     */
    protected function adaptSplits($subordinateSplits)
    {
        $subordinateSplitsData = [];
        foreach ($subordinateSplits as $split) {
            $splitObject = $this->objectFactory->create();
            $subordinateSplitsData[] = $splitObject->addData([
                "merchant_id" => $split['MerchantId'],
                "amount" => $split['Amount']
            ]);
        }

        return $subordinateSplitsData;
    }
}
