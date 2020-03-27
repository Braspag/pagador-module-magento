<?php

namespace Webjump\BraspagPagador\Model\Split;

use \Webjump\BraspagPagador\Api\Data\SplitItemInterface;

use Webjump\BraspagPagador\Model\ResourceModel\Split\Item as SplitItemResourceModel;

/**
 * Split Item Model
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class Item extends \Magento\Framework\Model\AbstractModel implements \Webjump\BraspagPagador\Api\Data\SplitItemInterface
{
    protected function _construct()
    {
        $this->_init(SplitItemResourceModel::class);
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
     * @return \Magento\Framework\Model\AbstractModel|Item
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @return mixed
     */
    public function getSplitId()
    {
        return $this->getData('split_id');
    }

    /**
     * @param $splitId
     * @return Item
     */
    public function setSplitId($splitId)
    {
        return $this->setData('split_id', $splitId);
    }

    /**
     * @return mixed
     */
    public function getSalesQuoteItemId()
    {
        return $this->getData('sales_quote_item_id');
    }

    /**
     * @param $salesQuoteItemId
     * @return Item
     */
    public function setSalesQuoteItemId($salesQuoteItemId)
    {
        return $this->setData('sales_quote_item_id', $salesQuoteItemId);
    }

    /**
     * @return mixed
     */
    public function getSalesOrderItemId()
    {
        return $this->getData('sales_order_item_id');
    }

    /**
     * @param $salesOrderItemId
     * @return Item
     */
    public function setSalesOrderItemId($salesOrderItemId)
    {
        return $this->setData('sales_order_item_id', $salesOrderItemId);
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
     * @return Item
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
     * @return Item
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('updated_at', $updatedAt);
    }
}
