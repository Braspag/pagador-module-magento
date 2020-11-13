<?php
/**
 * Capture Request
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\Lock;

use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Api\OAuth2TokenManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\Lock\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Model\InfoInterface;
use Webjump\BraspagPagador\Api\SplitDataProviderInterface;

class Request implements BraspaglibRequestInterface
{
    protected $session;
    protected $storeId;
    protected $config;
    protected $quote;
    protected $order;
    protected $oAuth2TokenManager;
    protected $isLocked;
    protected $subordinates;

    public function __construct(
        SessionManagerInterface $session,
        OAuth2TokenManagerInterface $oAuth2TokenManager
    ) {
        $this->setSession($session);
        $this->setOAuth2TokenManager($oAuth2TokenManager);
    }

    /**
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionManagerInterface $session
     */
    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeId = null)
    {
        $this->storeId = $storeId;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param mixed $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getOAuth2TokenManager()
    {
        return $this->oAuth2TokenManager;
    }

    /**
     * @param mixed $oAuth2TokenManager
     */
    public function setOAuth2TokenManager($oAuth2TokenManager)
    {
        $this->oAuth2TokenManager = $oAuth2TokenManager;
    }

    /**
     * @return mixed
     */
    public function isLocked()
    {
        return (bool) $this->isLocked;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->getOAuth2TokenManager()->getToken();
    }

    /**
     * @return mixed
     */
    public function isTestEnvironment()
    {
        return $this->getConfig()->getIsTestEnvironment();
    }

    /**
     * @return mixed
     */
    public function getOrderTransactionId()
    {
        if (empty($this->getOrder())) {
            return null;
        }
        return $this->getOrder()->getPayment()->getAuthorizationTransaction()->getTxnId();
    }

    /**
     * @param mixed $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
    }

    /**
     * @return mixed
     */
    public function getSubordinates()
    {
        return $this->subordinates;
    }

    /**
     * @param mixed $subordinates
     */
    public function setSubordinates($subordinates)
    {
        $this->subordinates = $subordinates;
    }
}
