<?php

namespace Webjump\BraspagPagador\Model;

use \Webjump\BraspagPagador\Api\Data\SplitInterface;

use Webjump\BraspagPagador\Model\ResourceModel\Split as SplitResourceModel;

/**
 * Split Model
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class Split extends \Magento\Framework\Model\AbstractModel implements \Webjump\BraspagPagador\Api\Data\SplitInterface
{
	protected function _construct()
	{
		$this->_init(SplitResourceModel::class);
	}

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param mixed $id
     * @return \Magento\Framework\Model\AbstractModel|Split
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @return mixed
     */
    public function getSubordinateMerchantId()
    {
        return $this->getData('subordinate_merchant_id');
    }

    /**
     * @param $subordinateMerchantId
     * @return Split
     */
    public function setSubordinateMerchantId($subordinateMerchantId)
    {
        return $this->setData('subordinate_merchant_id', $subordinateMerchantId);
    }

    /**
     * @return mixed
     */
    public function getStoreMerchantId()
    {
        return $this->getData('store_merchant_id');
    }

    /**
     * @param $storeMerchantId
     * @return Split
     */
    public function setStoreMerchantId($storeMerchantId)
    {
        return $this->setData('store_merchant_id', $storeMerchantId);
    }

    /**
     * @return mixed
     */
    public function getSalesQuoteId()
    {
        return $this->getData('sales_quote_id');
    }

    /**
     * @param $salesQuoteId
     * @return Split
     */
    public function setSalesQuoteId($salesQuoteId)
    {
        return $this->setData('sales_quote_id', $salesQuoteId);
    }

    /**
     * @return mixed
     */
    public function getSalesOrderId()
    {
        return $this->getData('sales_order_id');
    }

    /**
     * @param $salesOrderId
     * @return Split
     */
    public function setSalesOrderId($salesOrderId)
    {
        return $this->setData('sales_order_id', $salesOrderId);
    }

    /**
     * @return mixed
     */
    public function getMdrApplied()
    {
        return $this->getData('mdr_applied');
    }

    /**
     * @param $mdrApplied
     * @return Split
     */
    public function setMdrApplied($mdrApplied)
    {
        return $this->setData('mdr_applied', $mdrApplied);
    }

    /**
     * @return mixed
     */
    public function getTaxApplied()
    {
        return $this->getData('tax_applied');
    }

    /**
     * @param $taxApplied
     * @return Split
     */
    public function setTaxApplied($taxApplied)
    {
        return $this->setData('tax_applied', $taxApplied);
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->getData('total_amount');
    }

    /**
     * @param $totalAmount
     * @return Split
     */
    public function setTotalAmount($totalAmount)
    {
        return $this->setData('total_amount', $totalAmount);
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * @param $storeId
     * @return Split
     */
    public function setStoreId($storeId)
    {
        return $this->setData('store_id', $storeId);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @param $createdAt
     * @return Split
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }

    /**
     * @param $updatedAt
     * @return Split
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('updated_at', $updatedAt);
    }
}
