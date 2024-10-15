<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Braspag\BraspagPagador\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Braspag\BraspagPagador\Api\CardTokenManagerInterface;
use Braspag\BraspagPagador\Api\CardTokenRepositoryInterface;
use Braspag\BraspagPagador\Api\Data\CardTokenInterface;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;

class CardTokenManager implements CardTokenManagerInterface
{
    /**
     * @var
     */
    protected $cardTokenRepository;

    /**
     * @var
     */
    protected $eventManager;

    /**
     * @var
     */
    protected $searchCriteriaBuilder;

    /**
     * @var ConfigInterface
     */
    protected $config;


    protected $CardTokenRepository;

    /**
     * @var Encryptor
     */
    protected $encryptor;


    /**
     * CardTokenHandler constructor.
     *
     * @param CardTokenRepositoryInterface $cardTokenRepository
     * @param ManagerInterface             $eventManager
     * @param SearchCriteriaBuilder        $searchCriteriaBuilder
     */
    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository,
        ManagerInterface $eventManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ConfigInterface $config,
        Encryptor $encryptor

    ) {
        $this->setCardTokenRepository($cardTokenRepository);
        $this->setEventManager($eventManager);
        $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
        $this->setConfig($config);
        $this->encryptor = $encryptor;
    }

    public function registerCardToken($customerId, $paymentMethod, $response)
    {

        if ($cardToken = $this->getCardTokenRepository()->get($response->getPaymentCardToken())) {
            return $cardToken;
        }

        if ($this->getConfig()->isAutoDisablePreviousCardsToken()) {
            $searchCriteriaBuilder = $this->getSearchCriteriaBuilder();
            $searchCriteriaBuilder->addFilter('method', $paymentMethod);
            $searchCriteriaBuilder->addFilter('customer_id', $customerId);
            $searchCriteriaBuilder->addFilter('brand', $response->getPaymentCardBrand());
            $searchCriteria = $searchCriteriaBuilder->create();

            $searchResult = $this->getCardTokenRepository()->getList($searchCriteria);

            foreach ($searchResult->getItems() as $item) {
                $this->disable($item);
            }
        }

        $data = new DataObject([
            'alias' => $response->getPaymentCardNumberEncrypted(),
            'token' => $response->getPaymentCardToken(),
            'provider' => $response->getPaymentCardProvider(),
            'brand' => $response->getPaymentCardBrand(),
            'method' => $paymentMethod,
            'mask'   => $response->getPaymentAuthorizationCode(),
            'date_expiration_token' => $this->encryptor->encrypt($response->getPaymentCardExpirationDate())
        ]);

        $cardToken = $this->getCardTokenRepository()->create($data->toArray());

        $this->getCardTokenRepository()->save($cardToken);

        return $cardToken;
    }

    protected function deleteCardToken($cardToken)
    {
        $this->getCardTokenRepository()->delete($cardToken);
    }

    /**
     * @param $cardToken
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function disable(CardTokenInterface $cardToken)
    {
        try {
            $cardTokenId = $cardToken->getId();
            if (!empty($cardTokenId)) {
                $cardToken->setActive(0);
                $this->getCardTokenRepository()->save($cardToken);
            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to disable Card Token'));
        }
    }


    /**
     * @return mixed
     */
    protected function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * @param mixed $searchCriteriaBuilder
     */
    protected function setSearchCriteriaBuilder($searchCriteriaBuilder)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return mixed
     */
    protected function getCardTokenRepository()
    {
        return $this->CardTokenRepository;
    }

    /**
     * @param CardTokenRepositoryInterface $cardTokenRepository
     *
     * @return $this
     */
    protected function setCardTokenRepository(CardTokenRepositoryInterface $cardTokenRepository)
    {
        $this->CardTokenRepository = $cardTokenRepository;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param $eventManager
     *
     * @return $this
     */
    protected function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
