<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Braspag Transaction Billet Config
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Config extends BaseConfig implements ConfigInterface
{
	protected $date;

	public function __construct(
		ScopeConfigInterface $config,
		DateTime $date
	){
		$this->setConfig($config);
		$this->setDate($date);
	}

	public function getPaymentDemonstrative()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BILLET_DEMONSTRATIVE);
	}

	public function getPaymentInstructions()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BILLET_INSTRUCTIONS);
	}

	public function getPaymentAssignor()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BILLET_ASSIGNOR);
	}

	public function getExpirationDate()
	{
		return $this->getDate()->gmDate(self::DATE_FORMAT, strtotime(sprintf(self::DAY_FORMAT, (int) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BILLET_EXPIRATION_DATE))));
	}

	public function getPaymentProvider()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_BILLET_PROVIDER);
	}

    protected function getDate()
    {
        return $this->date;
    }

    protected function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }
}
