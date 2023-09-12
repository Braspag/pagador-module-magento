<?php

namespace Braspag\BraspagPagador\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Braspag\BraspagPagador\Model\Pix;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Payment\Model\Method\AbstractMethod;
use Braspag\BraspagPagador\Model\Config\Config;
use Magento\Store\Model\StoreManagerInterface;

class LogoConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string
     */
    protected $methodCode = Pix::PAYMENT_METHOD_PIX_CODE;

    /**
     * @var AbstractMethod
     */
    protected $method;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Scope config
     * @var Config
     */
    protected $config;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     *
     * @throws LocalizedException
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Config $config,
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->method = $paymentHelper->getMethodInstance($this->methodCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];

        if ($this->method->isAvailable()) {
            $config['payment']['logo'][$this->methodCode] = $this->storeManager->getStore()->getBaseUrl() . $this->storeManager->getStore()->getBaseMediaDir() . DIRECTORY_SEPARATOR . "payments/logo/" . DIRECTORY_SEPARATOR . $this->getLogo();
            $config['payment']['display_logo_title'][$this->methodCode] = "";
        }

        return $config;
    }

    /**
     * Get logo url from config
     *
     *
     * @return string
     */
    public function getLogo()
    {
        return nl2br($this->escaper->escapeHtml($this->config->getLogo()));
    }
}
