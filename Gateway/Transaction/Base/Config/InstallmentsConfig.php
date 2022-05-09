<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;

class InstallmentsConfig extends AbstractConfig implements InstallmentsConfigInterface
{
	protected $code;

    protected function _construct(array $data = [])
    {
        $code = '';
        if (isset($data['code'])) {
            $code = $data['code'];
        }

        $this->setCode($code);
    }

    public function isActive()
	{
		return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_IS_ACTIVE);
	}

	public function getInstallmentsNumber()
	{
		return (int) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_NUMBER);
	}

	public function isWithInterest()
	{
		return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_IS_WITH_INTEREST);
	}

	public function getInstallmentMinAmount()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_MIN_MOUNT);
	}

	public function getInterestRate()
	{
		return ((int) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_INTEREST_RATE) / 100);
	}

	public function isInterestByIssuer()
	{
		return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_INTEREST_BY_ISSUER);
	}

	public function getInstallmentsMaxWithoutInterest()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_MAX_WITHOUT_INTEREST);
	}

	protected function _getConfig($uri)
	{
		return parent::_getConfig(sprintf($uri, $this->getCode()));
	}

    protected function getCode()
    {
        return $this->code;
    }

    protected function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
