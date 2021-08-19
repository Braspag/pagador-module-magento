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
        return $this->adaptRequestData($splitPaymentData, $merchantId);
    }

    /**
     * @param array $splitPaymentData
     * @param null $merchantId
     * @return \Magento\Framework\DataObject
     */
    public function adaptRequestData(array $splitPaymentData, $merchantId = null)
    {
        $dataSplitPayments = $this->objectFactory->create();
        $splitPayments = [];
        foreach ($splitPaymentData as $subordinateId => $subordinate) {

            $subordinateDataObject = $this->objectFactory->create();

            $subordinateMerchantId = isset($subordinate['SubordinateMerchantId']) ?
                $subordinate['SubordinateMerchantId'] : $subordinateId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['Amount']) ? $subordinate['Amount'] : $subordinate['amount']
            ];

            if (isset($subordinate['fares'])) {

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

                $subordinateData['fares'] = $subordinateFaresDataObject;
            }

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
     * @param array $splitPaymentData
     * @param null $merchantId
     * @param null $responseDataType
     * @return \Magento\Framework\DataObject
     */
    public function adaptResponseData(array $splitPaymentData, $merchantId = null, $responseDataType = null)
    {
        switch ($responseDataType) {
            case 'authorize':
                return $this->adaptAuthorizedDataReceivingFromBraspag($splitPaymentData, $merchantId);
            break;

            case 'capture':
                return $this->adaptCapturedDataReceivingFromBraspag($splitPaymentData, $merchantId);
            break;

            case 'transaction_post':
                return $this->adaptTransactionPostDataReceivingFromBraspag($splitPaymentData, $merchantId);
            break;

            default:
                return $this->adaptAuthorizedDataReceivingFromBraspag($splitPaymentData, $merchantId);
            break;
        }
    }

    /**
     * @param array $splitPaymentData
     * @param null $merchantId
     * @return \Magento\Framework\DataObject
     */
    public function adaptAuthorizedDataReceivingFromBraspag(array $splitPaymentData, $merchantId = null)
    {
        $dataSplitPayments = $this->objectFactory->create();
        $splitPayments = [];

        if (!isset($splitPaymentData['Splits']) || !isset($splitPaymentData['Merchant']['Id'])) {
            return $dataSplitPayments;
        }

        $merchantId = $splitPaymentData['Merchant']['Id'];

        foreach ($splitPaymentData['Splits'] as $subordinate) {

            $subordinateDataObject = $this->objectFactory->create();

            $subordinateMerchantId = isset($subordinate['Merchant']['Id']) ?
                $subordinate['Merchant']['Id'] : $merchantId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['GrossAmount']) ? $subordinate['GrossAmount'] : 0
            ];

            if (isset($subordinate['Fares'])) {

                $subordinateFaresDataObject = $this->objectFactory->create();

                $subordinateFaresData = [
                    "mdr" => floatval(isset($subordinate['Fares']['Mdr']) ?
                        $subordinate['Fares']['Mdr'] : 0
                    ),
                    "fee" => floatval(isset($subordinate['Fares']['Fee']) ?
                        $subordinate['Fares']['Fee'] : 0
                    )
                ];

                $subordinateFaresDataObject->addData($subordinateFaresData);

                $subordinateData['fares'] = $subordinateFaresDataObject;
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
     * @param array $splitPaymentData
     * @param null $merchantId
     * @return \Magento\Framework\DataObject
     */
    public function adaptCapturedDataReceivingFromBraspag(array $splitPaymentData, $merchantId = null)
    {
        $dataSplitPayments = $this->objectFactory->create();
        $splitPayments = [];

        foreach ($splitPaymentData as $subordinate) {

            $subordinateDataObject = $this->objectFactory->create();

            $subordinateMerchantId = isset($subordinate['SubordinateMerchantId']) ?
                $subordinate['SubordinateMerchantId'] : $merchantId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['Amount']) ? $subordinate['Amount'] : 0
            ];

            if (isset($subordinate['Fares'])) {

                $subordinateFaresDataObject = $this->objectFactory->create();

                $subordinateFaresData = [
                    "mdr" => floatval(isset($subordinate['Fares']['Mdr']) ?
                        $subordinate['Fares']['Mdr'] : 0
                    ),
                    "fee" => floatval(isset($subordinate['Fares']['Fee']) ?
                        $subordinate['Fares']['Fee'] : 0
                    )
                ];

                $subordinateFaresDataObject->addData($subordinateFaresData);

                $subordinateData['fares'] = $subordinateFaresDataObject;
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
     * @param array $splitPaymentData
     * @param null $merchantId
     * @return \Magento\Framework\DataObject
     */
    public function adaptTransactionPostDataReceivingFromBraspag(array $splitPaymentData, $merchantId = null)
    {
        $dataSplitPayments = $this->objectFactory->create();
        $splitPayments = [];

        foreach ($splitPaymentData as $subordinate) {

            $subordinateDataObject = $this->objectFactory->create();

            $subordinateMerchantId = isset($subordinate['SubordinateMerchantId']) ?
                $subordinate['SubordinateMerchantId'] : $merchantId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['Amount']) ? $subordinate['Amount'] : 0
            ];

            if (isset($subordinate['Fares'])) {

                $subordinateFaresDataObject = $this->objectFactory->create();

                $subordinateFaresData = [
                    "mdr" => floatval(isset($subordinate['Fares']['Mdr']) ?
                        $subordinate['Fares']['Mdr'] : 0
                    ),
                    "fee" => floatval(isset($subordinate['Fares']['Fee']) ?
                        $subordinate['Fares']['Fee'] : 0
                    )
                ];

                $subordinateFaresDataObject->addData($subordinateFaresData);

                $subordinateData['fares'] = $subordinateFaresDataObject;
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
