<?php

namespace Webjump\BraspagPagador\Model\ResourceModel\Split\Item;

use Webjump\BraspagPagador\Model\ResourceModel\Split\Item as SplitItemResourceModel;
use Webjump\BraspagPagador\Model\Split\Item as SplitItemModel;

/**
 * Card Token Collection
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
	{
		$this->_init(
			SplitItemModel::class,
			SplitItemResourceModel::class
		);
	}
}