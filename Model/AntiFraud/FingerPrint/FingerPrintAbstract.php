<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Model\AntiFraud\FingerPrint;


use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

abstract class FingerPrintAbstract
{
    const XML_SRC_PNG_IMAGE_URL         = 'webjump_braspag_antifraud/fingerprint/src_png_img';
    const XML_SRC_JS_URL                = 'webjump_braspag_antifraud/fingerprint/src_js';
    const XML_SRC_FLASH_URL             = 'webjump_braspag_antifraud/fingerprint/src_flash';
    const XML_ORG_ID                    = 'webjump_braspag_antifraud/fingerprint/org_id';
    const XML_MERCHANT_ID                    = 'webjump_braspag_antifraud/fingerprint/merchant_id';
    const XML_ORDER_ID_TO_FINGERPRINT   = 'webjump_braspag_antifraud/fingerprint/use_order_id_to_fingerprint';

    protected $scopeConfig;
    protected $session;
    
    protected $srcPngImageUrl;
    protected $srcJsUrl;
    protected $srcFlashUrl;
    protected $orgId;
    protected $sessionId;
    protected $quote;
    protected $customerRepository;
    protected $quoteFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SessionManagerInterface $session,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        $this->setScopeConfig($scopeConfig);
        $this->setSession($session);
        $this->setCustomerRepository($customerRepository);
        $this->setQuoteFactory($quoteFactory);
    }

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @return $this
     */
    protected function setScopeConfig(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        return $this;
    }

    /**
     * @return ScopeConfigInterface
     */
    protected function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * @param SessionManagerInterface $session
     * @return $this
     */
    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        return $this->session;
    }

    protected function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return mixed
     */
    public function getCustomerRepository()
    {
        return $this->customerRepository;
    }

    /**
     * @param mixed $customerRepository
     */
    public function setCustomerRepository($customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return mixed
     */
    public function getQuoteFactory()
    {
        return $this->quoteFactory;
    }

    /**
     * @param mixed $quoteFactory
     */
    public function setQuoteFactory($quoteFactory)
    {
        $this->quoteFactory = $quoteFactory;
    }
}
