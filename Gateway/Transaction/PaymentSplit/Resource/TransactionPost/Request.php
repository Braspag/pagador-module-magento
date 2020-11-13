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
namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\TransactionPost;

use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Model\InfoInterface;
use Webjump\BraspagPagador\Api\SplitDataProviderInterface;
use Webjump\BraspagPagador\Model\OAuth2TokenManager;

class Request implements BraspaglibRequestInterface
{
    protected $session;
    protected $storeId;
    protected $config;
    protected $dataProvider;
    protected $quote;
    protected $order;
    protected $oAuth2TokenManager;
    protected $splits;

    public function __construct(
        SessionManagerInterface $session,
        SplitDataProviderInterface $dataProvider,
        OAuth2TokenManager $oAuth2TokenManager
    ) {
        $this->setSession($session);
        $this->setDataProvider($dataProvider);
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
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param mixed $dataProvider
     */
    public function setDataProvider($dataProvider)
    {
        $this->dataProvider = $dataProvider;
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
    public function getSplits()
    {
        return $this->splits;
    }

    /**
     * @param mixed $splits
     */
    public function setSplits($splits)
    {
        $this->splits = $splits;
    }

    /**
     * @return mixed
     */
    public function prepareSplits()
    {
        if (!$this->getConfig()->isPaymentSplitActive()) {
            return [];
        }

        $defaultMdr = $this->getConfig()->getPaymentSplitDefaultMrd();
        $defaultFee = $this->getConfig()->getPaymentSplitDefaultFee();
        $storeMerchantId = $this->getConfig()->getMerchantId();

        if (!empty($this->getQuote())) {
            $this->getDataProvider()->setQuote($this->getQuote());
        }

        if (!empty($this->getOrder())) {
            $this->getDataProvider()->setOrder($this->getOrder());
        }

        $this->setSplits($this->getDataProvider()->getData($storeMerchantId, $defaultMdr, $defaultFee));

        return $this;
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
}
