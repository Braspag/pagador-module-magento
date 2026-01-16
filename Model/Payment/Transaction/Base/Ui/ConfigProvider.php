<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\Base\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfig;

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
        $years = $this->getCcExpirationYears(16);

        return [
            'payment' => [
                'braspag' => [
                    'merchantId'    => '',
                    'merchantKey'   => '',
                    'isTestEnvironment'   => $this->getBaseConfig()->getIsTestEnvironment()
                ],
                'braspag_ccform' => [
                    'years' => [
                        \Braspag\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider::CODE => $years,
                        \Braspag\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider::CODE  => $years,
                        \Braspag\BraspagPagador\Model\Payment\Transaction\Voucher\Ui\ConfigProvider::CODE    => $years,
                    ]
                ]
            ],
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

    private function getCcExpirationYears(int $yearsToShow = 21): array
    {
        $start = (int)date('Y');

        if ($yearsToShow < 1) {
            $yearsToShow = 1;
        }

        $end = $start + ($yearsToShow - 1);

        $years = [];
        for ($y = $start; $y <= $end; $y++) {
            $years[(string)$y] = $y;
        }

        return $years;
    }
}