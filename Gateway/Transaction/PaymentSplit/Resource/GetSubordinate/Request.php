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
namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate;

use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Api\OAuth2TokenManagerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\GetSubordinate\RequestInterface as BraspaglibRequestInterface;

class Request implements BraspaglibRequestInterface
{
    protected $session;

    protected $storeId;

    protected $config;

    protected $oAuth2TokenManager;

    protected $subordinateId;

    protected $subordinateMerchantId;

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
    public function getSubordinateId()
    {
        return $this->subordinateId;
    }

    /**
     * @param mixed $subordinateId
     */
    public function setSubordinateId($subordinateId)
    {
        $this->subordinateId = $subordinateId;
    }

    /**
     * @return mixed
     */
    public function getSubordinateMerchantId()
    {
        return $this->subordinateMerchantId;
    }

    /**
     * @param mixed $subordinateMerchantId
     */
    public function setSubordinateMerchantId($subordinateMerchantId)
    {
        $this->subordinateMerchantId = $subordinateMerchantId;
    }
}
