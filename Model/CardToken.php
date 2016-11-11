<?php

namespace Webjump\BraspagPagador\Model;

USE \Webjump\BraspagPagador\Api\Data\CardTokenInterface;

/**
 * Card Token Model
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CardToken extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, \Webjump\BraspagPagador\Api\Data\CardTokenInterface
{
	const CACHE_TAG = 'webjump_braspagpagador_cardtoken';

	protected function _construct()
	{
		$this->_init('Webjump\BraspagPagador\Model\ResourceModel\CardToken');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

    public function getId()
    {
    	return $this->getData(self::ENTITY_ID);
    }

    public function getAlias()
    {
    	return $this->getData(self::ALIAS);
    }

    public function getToken()
    {
    	return $this->getData(self::TOKEN);
    }

    public function getCustomerId()
    {
    	return $this->getData(self::CUSTOMER_ID);
    }

    public function getStoreId()
    {
    	return $this->getData(self::STORE_ID);
    }	
}
