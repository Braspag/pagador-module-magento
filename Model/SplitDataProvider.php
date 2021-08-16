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

/**
 * Class SplitDataProvider
 * @package Webjump\BraspagPagador\Model
 */
class SplitDataProvider implements SplitDataProviderInterface
{
    protected $session;
    protected $config;
    protected $objectFactory;
    protected $storeManager;
    protected $splitAdapter;
    protected $quote;
    protected $order;
    protected $paymentSplitConfig;
    protected $marketplaceMerchantId;
    protected $marketplaceDefaultMdr = 0;
    protected $marketplaceDefaultFee = 0;
    protected $marketplaceSalesParticipation;
    protected $marketplaceSalesParticipationType;
    protected $marketplaceSalesParticipationPercent = 0;
    protected $marketplaceSalesParticipationFixedValue = 0;
    protected $marketplaceParticipationFinalValue = 0;
    protected $subordinates = [];

    /**
     * SplitDataProvider constructor.
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param StoreManagerInterface $storeManager
     * @param \Webjump\BraspagPagador\Model\SplitDataAdapter $splitAdapter
     * @param \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config\Config $paymentSplitConfig
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\DataObjectFactory $objectFactory,
        StoreManagerInterface $storeManager,
        SplitDataAdapter $splitAdapter,
        \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config\Config $paymentSplitConfig
    ) {
        $this->setObjectFactory($objectFactory);
        $this->setStoreManager($storeManager);
        $this->setSplitAdapter($splitAdapter);
        $this->setSession($session);
        $this->setPaymentSplitConfig($paymentSplitConfig);

        $this->marketplaceMerchantId = $this->paymentSplitConfig->getPaymentSplitMarketPlaceCredendialsMerchantId();
        $this->marketplaceSalesParticipation = (bool) $this->paymentSplitConfig->getPaymentSplitMarketPlaceGeneralSalesParticipation();
        $this->marketplaceSalesParticipationType = $this->paymentSplitConfig->getPaymentSplitMarketPlaceGeneralSalesParticipationType();
        $this->marketplaceSalesParticipationPercent = floatval($this->paymentSplitConfig->getPaymentSplitMarketPlaceGeneralSalesParticipationPercent());
        $this->marketplaceSalesParticipationFixedValue = floatval($this->paymentSplitConfig->getPaymentSplitMarketPlaceGeneralSalesParticipationFixedValue());
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
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param mixed $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
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
     * @return mixed
     */
    public function getPaymentSplitConfig()
    {
        return $this->paymentSplitConfig;
    }

    /**
     * @param mixed $paymentSplitConfig
     */
    public function setPaymentSplitConfig($paymentSplitConfig)
    {
        $this->paymentSplitConfig = $paymentSplitConfig;
    }

    /**
     * @param $storeMerchantId
     * @param int $storeDefaultMdr
     * @param int $storeDefaultFee
     * @return array
     */
    public function getData($storeMerchantId, $storeDefaultMdr = 0, $storeDefaultFee = 0)
    {
        if ($this->paymentSplitConfig->getPaymentSplitMarketPlaceVendor() !== 'braspag' ) {
            return [];
        }

        $this->marketplaceDefaultMdr = floatval($storeDefaultMdr);
        $this->marketplaceDefaultFee = floatval($storeDefaultFee);

        $dataCard = $this->subordinates = [];

        $items = [];
        $itemType = 'quote';

        if (!empty($this->getQuote())) {
            $items = $this->getQuote()->getAllVisibleItems();
            $itemType = 'quote';
        }

        if (empty($items) && !empty($this->getOrder())) {
            $items = $this->getOrder()->getAllVisibleItems();
            $itemType = 'order';
        }

        if (empty($items)) {
            $items = $this->getSession()->getQuote()->getAllVisibleItems();
            $itemType = 'quote';
        }

        foreach ($items as $item) {

            $product = $item->getProduct();

            $braspagSubordinateMerchantId = $product->getResource()
                ->getAttributeRawValue($product->getId(),'braspag_subordinate_merchantid',
                    $this->getStoreManager()->getStore()->getId()
                );

            if (empty($braspagSubordinateMerchantId)) {
                $braspagSubordinateMerchantId = $this->marketplaceMerchantId;
            }

            $braspagSubordinateMdr = $this->getSubordinateItemMdr($product);
            $braspagSubordinateFee = $this->getSubordinateItemFee($product);

            if (!isset($this->subordinates[$braspagSubordinateMerchantId])) {
                $this->subordinates[$braspagSubordinateMerchantId] = [];
                $this->subordinates[$braspagSubordinateMerchantId]['amount'] = 0;

                if ($braspagSubordinateMerchantId !== $this->marketplaceMerchantId) {
                    $this->subordinates[$braspagSubordinateMerchantId]['fares'] = [
                        "mdr" => floatval($braspagSubordinateMdr),
                        "fee" => floatval($braspagSubordinateFee)
                    ];
                }

                $this->subordinates[$braspagSubordinateMerchantId]['skus'] = [];
            }

            $itemPrice = floatval(($item->getPriceInclTax()*$item->getQty()) - $item->getDiscountAmount());

            $this->subordinates[$braspagSubordinateMerchantId]['amount'] += ($itemPrice * 100);

            $itemsObject = $this->objectFactory->create();
            $items = [
                "item_id" => $item->getId(),
                "item_type" => $itemType,
                "sku" => $product->getSku()
            ];

            $itemsObject->addData($items);

            $this->subordinates[$braspagSubordinateMerchantId]['items'][] =  $itemsObject;
        }

        if ($this->marketplaceSalesParticipation) {
            $this->removeMarketplaceParticipationValuesFromSubordinates();
            $this->addMarketplaceParticipationValues();
        }

        return $this->getSplitAdapter()->adaptRequestData($this->subordinates, $this->marketplaceMerchantId);
    }

    /**
     * @param $product
     * @return int
     */
    protected function getSubordinateItemMdr($product)
    {
        $braspagSubordinateMdr = $product->getResource()
            ->getAttributeRawValue(
                $product->getId(),
                'braspag_subordinate_mdr',
                $this->getStoreManager()->getStore()->getId()
            );

        if (empty($braspagSubordinateMdr)) {
            $braspagSubordinateMdr = $this->marketplaceDefaultMdr;
        }

        return $braspagSubordinateMdr;
    }

    /**
     * @param $product
     * @return int
     */
    protected function getSubordinateItemFee($product)
    {
        $braspagSubordinateFee = $product->getResource()
            ->getAttributeRawValue(
                $product->getId(),
                'braspag_subordinate_fee',
                $this->getStoreManager()->getStore()->getId()
            );

        if (empty($braspagSubordinateFee)) {
            $braspagSubordinateFee = $this->marketplaceDefaultFee;
        }

        return $braspagSubordinateFee;
    }

    /**
     * @return $this
     */
    protected function removeMarketplaceParticipationValuesFromSubordinates()
    {
        foreach ($this->subordinates as $subordinateId => $subordinateData) {

            $subordinateAmountOriginal = floatval($subordinateData['amount']) / 100;

            if ($this->marketplaceSalesParticipation && $subordinateId !== $this->marketplaceMerchantId) {

                $subordinateAmount = $subordinateAmountOriginal;

                if ($this->marketplaceSalesParticipationType === '1') {
                    $subordinateAmount = (floatval($this->marketplaceSalesParticipationPercent) / 100) * $subordinateAmount;
                }

                if ($this->marketplaceSalesParticipationType === '2'
                    && $subordinateAmount >= $this->marketplaceSalesParticipationFixedValue
                ) {
                    $subordinateAmount = floatval($subordinateAmount) - floatval($this->marketplaceSalesParticipationFixedValue);
                }

                $this->subordinates[$subordinateId]['amount'] = $subordinateAmount * 100;

                $this->marketplaceParticipationFinalValue += $subordinateAmountOriginal-$subordinateAmount;
            }
        }

        return $this;
    }

    /**
     * @return \Braspag\Unirgy\Plugin\SplitDataProvider
     */
    protected function addMarketplaceParticipationValues()
    {
        if (!isset($this->subordinates[$this->marketplaceMerchantId])) {
            $this->subordinates[$this->marketplaceMerchantId] = [];
            $this->subordinates[$this->marketplaceMerchantId]['amount'] = 0;
        }

        $this->subordinates[$this->marketplaceMerchantId]['amount'] += $this->marketplaceParticipationFinalValue * 100;

        return $this;
    }
}