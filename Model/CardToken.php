<?php

namespace Webjump\BraspagPagador\Model;

use \Webjump\BraspagPagador\Api\Data\CardTokenInterface;

use Webjump\BraspagPagador\Model\ResourceModel\CardToken as CardTokenResourceModel;

/**
 * Card Token Model
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class CardToken extends \Magento\Framework\Model\AbstractModel implements \Webjump\BraspagPagador\Api\Data\CardTokenInterface
{
	const CACHE_TAG = 'webjump_braspagpagador_cardtoken';

	protected function _construct()
	{
		$this->_init(CardTokenResourceModel::class);
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

    public function getMethod()
    {
        return $this->getData(self::METHOD);
    }

    public function getMask()
    {
        return $this->getData(self::MASK);
    }

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function setAlias($alias)
    {
        return $this->setData(self::ALIAS, $alias);
    }

    public function setToken($token)
    {
        return $this->setData(self::TOKEN, $token);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    public function isActive()
    {
        return (boolean) $this->getData(self::ACTIVE);
    }

    public function setActive($active)
    {
        return $this->setData(self::ACTIVE, (boolean) $active);
    }

    public function getBrand()
    {
        return $this->getData(self::BRAND);
    }

    public function setBrand($brand)
    {
        return $this->setData(self::BRAND, $brand);
    }

    public function getProvider()
    {
        return $this->getData(self::PROVIDER);
    }

    public function setProvider($provider)
    {
        return $this->setData(self::PROVIDER, $provider);
    }

    public function setMethod($method)
    {
        return $this->setData(self::METHOD, $method);
    }

    public function setMask($mask)
    {
        return $this->setData(self::MASK, $mask);
    }
}
