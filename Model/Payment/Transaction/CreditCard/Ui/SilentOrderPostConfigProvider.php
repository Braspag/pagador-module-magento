<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\BuilderInterface as SilentOrderPOstBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfigInterface;

/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class SilentOrderPostConfigProvider implements ConfigProviderInterface
{
    protected $code;

    protected $silentorderPostBuilder;

    protected $silentOrderPostConfig;

    public function __construct(
        $code,
        SilentOrderPOstBuilder $silentorderPostBuilder,
        SilentOrderPostConfigInterface $silentOrderPostConfig
    ) {
        $this->setCode($code);
        $this->setSilentorderPostBuilder($silentorderPostBuilder);
        $this->setSilentOrderPostConfig($silentOrderPostConfig);
    }

    public function getConfig()
    {
        if (!$active = $this->getSilentOrderPostConfig()->isActive()) {
            return [
                'payment' => [
                    'ccform' => [
                        'silentorderpost' => [
                            'active' => [$this->getCode() => $active],
                        ],
                    ]
                ]
            ];
        }

        return [
            'payment' => [
                'ccform' => [
                    'silentorderpost' => [
                        'active' => [$this->getCode() => $active],
                        'url' => [$this->getCode() => $this->getSilentOrderPostConfig()->getPaymentTokenUrl()],
                        'accesstoken' => [$this->getCode() => $this->getSilentorderPostBuilder()->build()],
                    ],
                ]
            ]
        ];
    }

    protected function getSilentorderPostBuilder()
    {
        return $this->silentorderPostBuilder;
    }

    protected function setSilentorderPostBuilder($silentorderPostBuilder)
    {
        $this->silentorderPostBuilder = $silentorderPostBuilder;

        return $this;
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

    protected function getSilentOrderPostConfig()
    {
        return $this->silentOrderPostConfig;
    }

    protected function setSilentOrderPostConfig($silentOrderPostConfig)
    {
        $this->silentOrderPostConfig = $silentOrderPostConfig;

        return $this;
    }
}
