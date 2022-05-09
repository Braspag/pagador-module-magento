<?php
namespace Webjump\BraspagPagador\Helper\GrandTotal;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;

/**
 * Pricing data helper
 *
 * @api
 */
class Pricing extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        PriceCurrencyInterface $priceCurrency,
        ConfigInterface $config
    ) {
        parent::__construct($context);
        $this->priceCurrency =  $priceCurrency;
        $this->config = $config;
    }

    /**
     * Convert and format price value for current application store
     *
     * @param   float $value
     * @return  int|string
     */
    public function currency($value)
    {
        $precision = $this->config->getDecimalGrandTotal();
        $roundedValue = $this->priceCurrency->convertAndRound($value, null, null, $precision);
        $expendedValue = $roundedValue * 100;
        $integerValue = str_replace('.', '', $expendedValue);
        return $integerValue;
    }
}
