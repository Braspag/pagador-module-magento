<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\Base\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface  as InstallmentsBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface as InstallmentsConfig;

/**
 * Braspag Transaction Base Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
abstract class AbstractInstallmentsConfigProvider implements ConfigProviderInterface
{
    const CODE = null;

    protected $installments = [];

    protected $installmentsBuilder;

    protected $installmentsConfig;

    public function __construct(
        InstallmentsBuilder $installmentsBuilder,
        InstallmentsConfig $installmentsConfig
    ) {
        $this->setInstallmentsBuilder($installmentsBuilder);
        $this->setInstallmentsConfig($installmentsConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'installments' => [
                        'active' => [$this::CODE => $this->getInstallmentsConfig()->isActive()],
                        'list' => $this->getInstallments(),
                    ],
                ]
            ]
        ];

        return $config;
    }

    protected function getInstallments()
    {
        if (!$this->getInstallmentsConfig()->isActive()) {
            return $this->installments[$this::CODE] = [];
        }

        foreach ($this->getInstallmentsBuilder()->build() as $installment) {
            $this->installments[$this::CODE][$installment->getId()] = $installment->getLabel();
        }

    	return $this->installments;
    }

    protected function getInstallmentsBuilder()
    {
        return $this->installmentsBuilder;
    }

    protected function setInstallmentsBuilder($installmentsBuilder)
    {
        $this->installmentsBuilder = $installmentsBuilder;

        return $this;
    }

    protected function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    protected function setInstallmentsConfig($installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }
}
