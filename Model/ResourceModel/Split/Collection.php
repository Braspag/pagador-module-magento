<?php

namespace Webjump\BraspagPagador\Model\ResourceModel\Split;

use Webjump\BraspagPagador\Model\ResourceModel\Split as SplitResourceModel;
use Webjump\BraspagPagador\Model\Split as SplitModel;

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
			SplitModel::class,
			SplitResourceModel::class
		);
	}
}