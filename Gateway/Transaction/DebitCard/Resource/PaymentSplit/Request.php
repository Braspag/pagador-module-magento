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
namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\PaymentSplit;

use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BraspagMagentoRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\PaymentSplit\RequestInterface as BraspaglibRequestInterface;
use Magento\Payment\Model\InfoInterface;
use Webjump\BraspagPagador\Api\SplitDataProviderInterface;

class Request implements BraspaglibRequestInterface
{
    protected $quote;
    protected $session;
    protected $storeId;
    protected $config;
    protected $dataProvider;

    public function __construct(
        SessionManagerInterface $session,
        SplitDataProviderInterface $dataProvider
    ) {
        $this->setSession($session);
        $this->setDataProvider($dataProvider);
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
    public function getSplits()
    {
        if (!$this->getConfig()->hasPaymentSplit()) {
            return [];
        }

        $defaultMdr = $this->getConfig()->getPaymentSplitDefaultMrd();
        $defaultFee = $this->getConfig()->getPaymentSplitDefaultFee();
        $storeMerchantId = $this->getConfig()->getMerchantId();

        return $this->getDataProvider()->getData($storeMerchantId, $defaultMdr, $defaultFee);
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
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (! $this->quote) {
            $this->quote =  $this->getSession()->getQuote();
        }

        return $this->quote;
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
}
