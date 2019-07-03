<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfig;

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

	public function __construct(
		BaseConfig $baseConfig
	) {
		$this->setBaseConfig($baseConfig);
	}

    public function getConfig()
    {
        return [
            'payment' => [
        		'braspag' => [
                    'merchantId'    => '',
                    'merchantKey'   => '',
                    'isTestEnvironment'   => $this->getBaseConfig()->getIsTestEnvironment()
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
