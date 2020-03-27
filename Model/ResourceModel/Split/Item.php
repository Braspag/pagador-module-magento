<?php

namespace Webjump\BraspagPagador\Model\ResourceModel\Split;

/**
 * Split Item Resource
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('braspag_paymentsplit_split_item', 'split_item_id');
	}
}
