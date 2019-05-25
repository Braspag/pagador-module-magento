<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as DebitCardConfig;

/**
 * Braspag Transaction Base Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
	protected $baseConfig;
	protected $creditCardConfig;
	protected $debitCardConfig;

	public function __construct(
		BaseConfig $baseConfig,
        CreditCardConfig $creditCardConfig,
        DebitCardConfig $debitCardConfig
	) {
		$this->setBaseConfig($baseConfig);
		$this->creditCardConfig = $creditCardConfig;
		$this->debitCardConfig = $debitCardConfig;
	}

    public function getConfig()
    {
        return [
            'payment' => [
        		'braspag' => [
                    'merchantId'    => $this->getBaseConfig()->getMerchantId(),
                    'merchantKey'   => $this->getBaseConfig()->getMerchantKey()
                ]
        	]
        ];
    }

    protected function getBaseConfig()
    {
        return $this->baseConfig;
    }

    protected function setBaseConfig($baseConfig)
    {
        $this->baseConfig = $baseConfig;

        return $this;
    }
}
