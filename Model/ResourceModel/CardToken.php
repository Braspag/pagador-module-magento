<?php

namespace Webjump\BraspagPagador\Model\ResourceModel;

/**
 * Card Token Resource
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class CardToken extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('webjump_braspagpagador_cardtoken', 'entity_id');
	}
}
