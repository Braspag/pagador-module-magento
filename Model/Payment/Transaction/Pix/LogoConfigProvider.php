<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\Pix;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
// use Braspag\BraspagPagador\Model\Pix;
use Braspag\BraspagPagador\Model\Method\Pix\Ui\ConfigProvider;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Payment\Model\Method\AbstractMethod;

class LogoConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string
     */
    // protected $methodCode = Pix::PAYMENT_METHOD_PIX_CODE;
    protected $methodCode = ConfigProvider::CODE;

    /**
     * @var AbstractMethod
     */
    protected $method;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     *
     * @throws LocalizedException
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
        $this->method = $paymentHelper->getMethodInstance($this->methodCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];

        if ($this->method->isAvailable()) {
            $config['payment']['logo'][$this->methodCode] = $this->getLogo($this->methodCode);
            $config['payment']['display_logo_title'][$this->methodCode] = $this->method->displayTitleLogo();
        }

        return $config;
    }

    /**
     * Get logo url from config
     *
     * @param string $code
     *
     * @return string
     */
    protected function getLogo($code)
    {
        return nl2br($this->escaper->escapeHtml($this->method->getLogo()));
    }
}