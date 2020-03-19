<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Webjump\BraspagPagador\Api\SplitManagerInterface;
use Webjump\BraspagPagador\Api\SplitRepositoryInterface;
use Webjump\BraspagPagador\Api\SplitItemRepositoryInterface;
use Webjump\BraspagPagador\Api\Data\SplitInterface;
use Magento\Store\Model\StoreManagerInterface;
use Webjump\BraspagPagador\Model\Source\Status\NewPending as OrderStatusesNew;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;

class SplitManager implements SplitManagerInterface
{
    /**
     * @var
     */
    protected $splitRepository;

    /**
     * @var
     */
    protected $splitItemRepository;

    /**
     * @var
     */
    protected $eventManager;

    /**
     * @var
     */
    protected $searchCriteriaBuilder;

    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var
     */
    protected $orderStatusesNew;

    /**
     * @var
     */
    protected $orderFactory;

    /**
     * PaymentSplitManager constructor.
     * @param SplitRepositoryInterface $splitRepository
     * @param SplitItemRepositoryInterface $splitItemRepository
     * @param ManagerInterface $eventManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        SplitRepositoryInterface $splitRepository,
        SplitItemRepositoryInterface $splitItemRepository,
        ManagerInterface $eventManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        OrderStatusesNew $orderStatusesNew,
        OrderFactory $orderFactory
    ) {
        $this->setSplitRepository($splitRepository);
        $this->setSplitItemRepository($splitItemRepository);
        $this->setEventManager($eventManager);
        $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
        $this->setStoreManager($storeManager);
        $this->setOrderStatusesNew($orderStatusesNew);
        $this->setOrderFactory($orderFactory);
    }

    /**
     * @return mixed
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * @param mixed $searchCriteriaBuilder
     */
    protected function setSearchCriteriaBuilder($searchCriteriaBuilder)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return mixed
     */
    protected function getSplitRepository()
    {
        return $this->splitRepository;
    }

    /**
     * @param CardTokenRepositoryInterface $cardTokenRepository
     *
     * @return $this
     */
    protected function setSplitRepository(SplitRepositoryInterface $splitRepository)
    {
        $this->splitRepository = $splitRepository;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param $eventManager
     *
     * @return $this
     */
    protected function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSplitItemRepository()
    {
        return $this->splitItemRepository;
    }

    /**
     * @param mixed $splitItemRepository
     */
    public function setSplitItemRepository($splitItemRepository)
    {
        $this->splitItemRepository = $splitItemRepository;
    }

    /**
     * @return mixed
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @param mixed $storeManager
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @return mixed
     */
    public function getOrderStatusesNew()
    {
        return $this->orderStatusesNew;
    }

    /**
     * @param mixed $orderStatusesNew
     */
    public function setOrderStatusesNew($orderStatusesNew)
    {
        $this->orderStatusesNew = $orderStatusesNew;
    }

    /**
     * @return mixed
     */
    public function getOrderFactory()
    {
        return $this->orderFactory;
    }

    /**
     * @param mixed $orderFactory
     */
    public function setOrderFactory($orderFactory)
    {
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param Quote $quote
     * @param DataObject $splitPaymentData
     * @return $this
     */
    public function createPaymentSplitByQuote(Quote $quote, DataObject $splitPaymentData)
    {
        $paymentSplit = $this->getPaymentSplitByQuote($quote,  $splitPaymentData->getStoreMerchantId());

        $paymentSplit
            ->setSubordinateMerchantId($splitPaymentData->getStoreMerchantId())
            ->setStoreMerchantId($splitPaymentData->getStoreMerchantId())
            ->setStoreId($this->getStoreManager()->getStore()->getId())
            ->setSalesQuoteId($quote->getId())
            ->save();

        foreach ($splitPaymentData->getSubordinates() as $splitSubordinate) {

            $paymentSplitSubordinate = $this
                ->getPaymentSplitByQuote($quote, $splitSubordinate->getSubordinateMerchantId());

            $paymentSplitSubordinate
                ->setSubordinateMerchantId($splitSubordinate->getSubordinateMerchantId())
                ->setStoreMerchantId($splitPaymentData->getStoreMerchantId())
                ->setTotalAmount($splitSubordinate->getAmount())
                ->setSalesQuoteId($quote->getId())
                ->setStoreId($this->getStoreManager()->getStore()->getId())
                ->setMdrApplied(floatval($splitSubordinate->getFares()->getMdr()))
                ->setTaxApplied(floatval($splitSubordinate->getFares()->getFee()))
                ->save();

            foreach ($splitSubordinate->getItems() as $item) {

                $paymentSplitItem = $this->getPaymentSplitByQuoteItem($item->getId(), $paymentSplit);

                $paymentSplitItem->setSplitId($paymentSplit->getId())
                    ->setSalesQuoteItemId($item->getItemId())
                    ->save();
            }
        }

        return $this;
    }

    /**
     * @param Order $order
     * @param DataObject $splitPaymentData
     * @return $this
     */
    public function createPaymentSplitByOrder(Order $order, DataObject $splitPaymentData)
    {
        foreach ($splitPaymentData->getSubordinates() as $splitSubordinate) {

            $paymentSplit = $this->getPaymentSplitByOrder($order, $splitSubordinate->getSubordinateMerchantId());

            $paymentSplit
                ->setTotalAmount($splitSubordinate->getAmount())
                ->setStoreId($this->getStoreManager()->getStore()->getId())
                ->setSalesOrderId($order->getId())
                ->setMdrApplied(floatval($splitSubordinate->getFares()->getMdr()))
                ->setTaxApplied(floatval($splitSubordinate->getFares()->getFee()))
                ->save();

            foreach ($splitSubordinate->getSplits() as $split) {

                $paymentSplits = $this->getPaymentSplitByOrder($order, $split->getMerchantId());

                $paymentSplits->setAmount($paymentSplits->getAmount() + $split->getAmount())
                    ->setSalesOrderId($order->getId())
                    ->save();
            }

            foreach ($order->getAllVisibleItems() as $item) {

                $paymentSplitItem = $this->getPaymentSplitByOrderItem($item->getQuoteItemId(), $paymentSplit);

                if (!empty($paymentSplitItem->getSalesQuoteItemId())) {
                    $paymentSplitItem
                        ->setSalesOrderItemId($item->getItemId())
                        ->save();
                }
            }
        }

        return $this;
    }

    /**
     * @param $quote
     * @param $splitSubordinateMerchantId
     * @return mixed
     */
    public function getPaymentSplitByQuote($quote, $splitSubordinateMerchantId)
    {
        $paymentSplit = $this->getSplitRepository()->create();
        $paymentSplitCollection = $paymentSplit->getCollection();

        $paymentSplitCollection->addFieldToFilter('sales_quote_id', $quote->getId());
        $paymentSplitCollection->addFieldToFilter('store_id', $quote->getStoreId());
        $paymentSplitCollection->addFieldToFilter('store_merchant_id', $splitSubordinateMerchantId);
        $paymentSplitFromCollection = $paymentSplitCollection->getFirstItem();

        if (!empty($paymentSplitFromCollection->getId())) {
            return $paymentSplitFromCollection;
        }

        return $paymentSplit;
    }

    /**
     * @param $quoteItemId
     * @param $paymentSplit
     * @return mixed
     */
    public function getPaymentSplitByQuoteItem($quoteItemId, $paymentSplit)
    {
        $paymentSplitItem = $this->getSplitItemRepository()->create();
        $paymentSplitItemCollection = $paymentSplitItem->getCollection();

        $paymentSplitItemCollection->addFieldToFilter('split_id', $paymentSplit->getId());
        $paymentSplitItemCollection->addFieldToFilter('sales_quote_item_id', $quoteItemId);
        $paymentSplitItemFromCollection = $paymentSplitItemCollection->getFirstItem();

        if (!empty($paymentSplitItemFromCollection->getId())) {
            return $paymentSplitItemFromCollection;
        }

        return $paymentSplitItem;
    }

    /**
     * @param $order
     * @param $splitSubordinateMerchantId
     * @return mixed
     */
    public function getPaymentSplitByOrder($order, $splitSubordinateMerchantId)
    {
        $paymentSplit = $this->getSplitRepository()->create();
        $paymentSplitCollection = $paymentSplit->getCollection();

        $paymentSplitCollection->addFieldToFilter('sales_quote_id', $order->getQuoteId());
        $paymentSplitCollection->addFieldToFilter('store_id', $order->getStoreId());
        $paymentSplitCollection->addFieldToFilter('subordinate_merchant_id', $splitSubordinateMerchantId);
        $paymentSplitFromCollection = $paymentSplitCollection->getFirstItem();

        if (!empty($paymentSplitFromCollection->getId())) {
            return $paymentSplitFromCollection;
        }

        return $paymentSplit;
    }

    /**
     * @param $orderItemId
     * @param $paymentSplit
     * @return mixed
     */
    public function getPaymentSplitByOrderItem($orderItemId, $paymentSplit)
    {
        $paymentSplitItem = $this->getSplitItemRepository()->create();
        $paymentSplitItemCollection = $paymentSplitItem->getCollection();

        $paymentSplitItemCollection->addFieldToFilter('split_id', $paymentSplit->getId());
        $paymentSplitItemCollection->addFieldToFilter('sales_order_item_id', $orderItemId);
        $paymentSplitItemFromCollection = $paymentSplitItemCollection->getFirstItem();

        if (!empty($paymentSplitItemFromCollection->getId())) {
            return $paymentSplitItemFromCollection;
        }

        return $paymentSplitItem;
    }

    /**
     * @param int $days
     * @return mixed
     */
    public function getTransactionPostOrdersToExecuteByDays($days = 25)
    {
        $collection = $this->getOrderFactory()->create()->getCollection();

        $orderStatusesNew = array_map(function($item){
            return $item['value'];
        }, $this->getOrderStatusesNew()->toOptionArray());

        array_shift($orderStatusesNew);

        $orderStatusesNew[] = 'complete';

        $collection->addAttributeToFilter("status", array('in' => $orderStatusesNew));

        $collection->getSelect()
            ->joinLeft(['bps' => 'braspag_paymentsplit_split'], 'main_table.entity_id = bps.sales_order_id')
            ->joinInner(['sop' => 'sales_order_payment'], 'main_table.entity_id = sop.parent_id')
            ->where("bps.sales_order_id IS NULL")
            ->where("sop.method = '".ConfigProvider::CODE."'")
//            ->where("DATE_FORMAT(DATE_ADD(main_table.created_at, INTERVAL {$days} DAY), \"%Y-%m-%d\") = DATE_FORMAT(NOW(), \"%Y-%m-%d\")")
            ->limit(100)
        ;

        return $collection;
    }
}
