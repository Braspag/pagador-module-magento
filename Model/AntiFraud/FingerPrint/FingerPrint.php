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


use Webjump\BraspagPagador\Api\Data\AntiFraudFingerPrintInterface;

class FingerPrint extends FingerPrintAbstract implements AntiFraudFingerPrintInterface
{

    /**
     * @return string
     */
    public function getSrcPngImageUrl()
    {
        if (! $this->srcPngImageUrl) {
            $this->srcPngImageUrl =  $this->getScopeConfig()->getValue(self::XML_SRC_PNG_IMAGE_URL);
        }

        return $this->srcPngImageUrl;
    }

    /**
     * @return string
     */
    public function getSrcJsUrl()
    {
        if (! $this->srcJsUrl) {
            $this->srcJsUrl =  $this->getScopeConfig()->getValue(self::XML_SRC_JS_URL);
        }

        return $this->srcJsUrl;
    }

    /**
     * @return string
     */
    public function getSrcFlashUrl()
    {
        if (! $this->srcFlashUrl) {
            $this->srcFlashUrl =  $this->getScopeConfig()->getValue(self::XML_SRC_FLASH_URL);
        }

        return $this->srcFlashUrl;
    }

    public function getOrgId()
    {
        if (! $this->orgId) {
            $this->orgId =  $this->getScopeConfig()->getValue(self::XML_ORG_ID);
        }

        return $this->orgId;
    }

    public function getSessionId()
    {
        if (! $this->sessionId) {
            $sessionId = $this->getSession()->getId();                                                                                                                                         ;
            if ($this->getScopeConfig()->getValue(self::XML_ORDER_ID_TO_FINGERPRINT)) {
                $sessionId =  $this->getReservedOrderId();
            }
            $merchantId = $this->getScopeConfig()->getValue(self::XML_MERCHANT_ID);
            $this->sessionId = $merchantId . $sessionId ;
        }

        return $this->sessionId;
    }
    
    protected function getReservedOrderId()
    {
        $quote = $this->getQuote();

        if (!$quote->getReservedOrderId()) {
            $quote->reserveOrderId();
            $quote->save();
        }
        
        return $quote->getReservedOrderId();
    }
}
