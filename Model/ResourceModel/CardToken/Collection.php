<?php

namespace Webjump\BraspagPagador\Model\ResourceModel\CardToken;

/**
 * Card Token Collection
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
	{
		$this->_init('Webjump\BraspagPagador\Model\CardToken', 'Webjump\BraspagPagador\Model\ResourceModel\CardToken');
	}
}