<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Webjump\BraspagPagador\Model\AntiFraud\FingerPrint\FingerPrint;
use Webjump\BraspagPagador\Api\Data\AntiFraudFingerPrintInterface;
use Webjump\BraspagPagador\Api\AntiFraudManagerInterface;
use Magento\Framework\DataObject;

class AntiFraudManager implements AntiFraudManagerInterface
{
    protected $fingerPrint;
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $customerSession;
    protected $dataObject;
    protected $quoteFactory;

    /**
     * AntiFraudManager constructor.
     * @param FingerPrint $fingerPrint
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param DataObject $dataObject
     */
    public function __construct(
        FingerPrint $fingerPrint,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        DataObject $dataObject
    ){
        $this->setFingerPrint($fingerPrint);
        $this->setCookieManager($cookieManager);
        $this->setCookieMetadataFactory($cookieMetadataFactory);
        $this->setCustomerSession($customerSession);
        $this->setDataObject($dataObject);
        $this->setQuoteFactory($quoteFactory);
    }

    /**
     * @return mixed
     */
    public function getFingerPrint()
    {
        return $this->fingerPrint;
    }

    /**
     * @param mixed $fingerPrint
     */
    public function setFingerPrint($fingerPrint)
    {
        $this->fingerPrint = $fingerPrint;
    }

    /**
     * @return mixed
     */
    public function getCookieManager()
    {
        return $this->cookieManager;
    }

    /**
     * @param mixed $cookieManager
     */
    public function setCookieManager($cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * @return mixed
     */
    public function getCookieMetadataFactory()
    {
        return $this->cookieMetadataFactory;
    }

    /**
     * @param mixed $cookieMetadataFactory
     */
    public function setCookieMetadataFactory($cookieMetadataFactory)
    {
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @return mixed
     */
    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * @param mixed $customerSession
     */
    public function setCustomerSession($customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }

    /**
     * @param mixed $dataObject
     */
    public function setDataObject($dataObject)
    {
        $this->dataObject = $dataObject;
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

    public function getFingerPrintData($customerId)
    {
        $customerSession = $this->getCustomerSession()->create();
        $customerSession->setCustomerId($customerId);
        $customer = $customerSession->getCustomer();

        $quote = $this->getQuoteFactory()->create()->loadByCustomer($customer);

        return [
            [
                'org_id' => $this->fingerPrint->getOrgId(),
                'session_id' => $this->fingerPrint->getSessionId(false, $quote)
            ]
        ];
    }
}
