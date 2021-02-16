<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigUrl
{
    const VALUE_URL = 'web/secure/base_url';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigUrl constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get 'web/unsecure/base_url'
     *
     * @return mixed
     */
    public function getValueUrl()
    {
        return $this->scopeConfig->getValue(self::VALUE_URL);
    }
}
