<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Framework\DataObject;
use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Api\SplitDataProviderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Webjump\BraspagPagador\Model\SplitDataAdapter;

class SplitDataProvider implements SplitDataProviderInterface
{
    /**
     * @var
     */
    protected $session;

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $objectFactory;

    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var
     */
    protected $splitAdapter;

    /**
     * @var
     */
    protected $order;

    /**
     * PaymentSplitManager constructor.
     * @param SplitRepositoryInterface $splitRepository
     * @param SplitItemRepositoryInterface $splitItemRepository
     * @param ManagerInterface $eventManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\DataObjectFactory $objectFactory,
        StoreManagerInterface $storeManager,
        SplitDataAdapter $splitAdapter
    ) {
        $this->setObjectFactory($objectFactory);
        $this->setStoreManager($storeManager);
        $this->setSplitAdapter($splitAdapter);
        $this->setSession($session);
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
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
    public function getSplitAdapter()
    {
        return $this->splitAdapter;
    }

    /**
     * @param mixed $splitAdapter
     */
    public function setSplitAdapter($splitAdapter)
    {
        $this->splitAdapter = $splitAdapter;
    }

    /**
     * @return mixed
     */
    public function getObjectFactory()
    {
        return $this->objectFactory;
    }

    /**
     * @param mixed $objectFactory
     */
    public function setObjectFactory($objectFactory)
    {
        $this->objectFactory = $objectFactory;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @param int $defaultMdr
     * @param int $defaultFee
     * @return array
     */
    public function getData($storeMerchantId, $storeDefaultMdr = 0, $storeDefaultFee = 0)
    {
        $dataCard = $subordinates = [];

        $items = [];

        if (!empty($this->getOrder())) {
            $items = $this->getOrder()->getAllVisibleItems();
        }

        if (empty($items)) {
            $items = $this->getSession()->getQuote()->getAllVisibleItems();
        }

        foreach ($items as $item) {

            $product = $item->getProduct();

            $braspagSubordinateMerchantId = $product->getResource()
                ->getAttributeRawValue($product->getId(),'braspag_subordinate_merchantid',
                    $this->getStoreManager()->getStore()->getId()
                );

            if (empty($braspagSubordinateMerchantId)) {
                return $dataCard;
            }

            if (!isset($subordinates[$braspagSubordinateMerchantId])) {
                $subordinates[$braspagSubordinateMerchantId] = [];
                $subordinates[$braspagSubordinateMerchantId]['amount'] = 0;
                $subordinates[$braspagSubordinateMerchantId]['fares'] = [
                    "mdr" => floatval($storeDefaultMdr),
                    "fee" => floatval($storeDefaultFee)
                ];
                $subordinates[$braspagSubordinateMerchantId]['skus'] = [];
            }

            $subordinates[$braspagSubordinateMerchantId]['amount'] +=  floatval($item->getRowTotalInclTax()) * 100;

            $itemsObject = $this->objectFactory->create();
            $items = [
                "item_id" => $item->getId(),
                "sku" => $product->getSku()
            ];

            $itemsObject->addData($items);

            $subordinates[$braspagSubordinateMerchantId]['items'][] =  $itemsObject;
        }

        $dataSplitPayment = $this->getSplitAdapter()->adapt($subordinates, $storeMerchantId);

        return $dataSplitPayment;
    }
}
