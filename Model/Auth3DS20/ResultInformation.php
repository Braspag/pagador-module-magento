<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Webjump\BraspagPagador\Api\Data\Auth3DS20InformationInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20InformationInterfaceFactory;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config as DebitConfig;
use Magento\Quote\Model\QuoteRepository;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Webjump\BraspagPagador\Api\Auth3DS20GetAddressInterface;
use Webjump\BraspagPagador\Api\Auth3DS20GetCartInterface;
use Webjump\BraspagPagador\Api\Auth3DS20UserAccountInterface;
use Webjump\BraspagPagador\Model\Auth3DS20\ConfigUrl;

/**
 * Class Auth3DS20ResultInformation
 *
 * @package Webjump\BraspagPagador\Model
 */
class ResultInformation implements \Webjump\BraspagPagador\Api\Auth3DS20ResultInformationInterface
{
    /**
     * @var Auth3DS20InformationInterfaceFactory
     */
    private $auth3DS20InformationFactory;

    /**
     * @var Config
     */
    private $creditConfig;

    /**
     * @var DebitConfig
     */
    private $debitConfig;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * @var Auth3DS20GetAddressInterface
     */
    private $auth3DS20GetAddress;

    /**
     * @var Auth3DS20GetCartInterface
     */
    private $auth3DS20GetCart;

    /**
     * @var Auth3DS20UserAccountInterface
     */
    private $auth3DS20UserAccount;

    /**
     * @var ConfigUrl
     */
    private $configUrl;

    /**
     * Auth3DS20ResultInformation constructor.
     *
     * @param Auth3DS20InformationInterfaceFactory $auth3DS20InformationFactory
     * @param Config $creditConfig
     * @param DebitConfig $debitConfig
     * @param QuoteRepository $quoteRepository
     * @param PriceHelper $priceHelper
     * @param Auth3DS20GetAddressInterface $auth3DS20GetAddress
     * @param Auth3DS20GetCartInterface $auth3DS20GetCart
     * @param Auth3DS20UserAccountInterface $auth3DS20UserAccount
     * @param ConfigUrl $configUrl
     */
    public function __construct(
        Auth3DS20InformationInterfaceFactory $auth3DS20InformationFactory,
        Config $creditConfig,
        DebitConfig $debitConfig,
        QuoteRepository $quoteRepository,
        PriceHelper $priceHelper,
        Auth3DS20GetAddressInterface $auth3DS20GetAddress,
        Auth3DS20GetCartInterface $auth3DS20GetCart,
        Auth3DS20UserAccountInterface $auth3DS20UserAccount,
        ConfigUrl $configUrl
    ){
        $this->auth3DS20InformationFactory = $auth3DS20InformationFactory;
        $this->creditConfig = $creditConfig;
        $this->debitConfig = $debitConfig;
        $this->quoteRepository = $quoteRepository;
        $this->priceHelper = $priceHelper;
        $this->auth3DS20GetAddress = $auth3DS20GetAddress;
        $this->auth3DS20GetCart = $auth3DS20GetCart;
        $this->auth3DS20UserAccount = $auth3DS20UserAccount;
        $this->configUrl = $configUrl;
    }

    /**
     * @inheritDoc
     */
    public function getInformation(int $cartId): \Webjump\BraspagPagador\Api\Data\Auth3DS20InformationInterface
    {
        /** @var $auth3DS20Information Auth3DS20InformationInterface */
        $auth3DS20Information = $this->auth3DS20InformationFactory->create();
        $quote = $this->quoteRepository->get($cartId);

        $auth3DS20Information->setBpmpiCreditAuthNotifyonly($this->creditConfig->isAuth3Ds20MCOnlyNotifyActive());
        $auth3DS20Information->setBpmpiDebitAuth($this->debitConfig->isAuth3Ds20Active());
        $auth3DS20Information->setBpmpiCreditAuthNotifyonly($this->creditConfig->isAuth3Ds20MCOnlyNotifyActive());
        $auth3DS20Information->setBpmpiDebitAuthNotifyonly($this->debitConfig->isAuth3Ds20MCOnlyNotifyActive());
        $auth3DS20Information->setBpmpiCurrency('BRL');
        $auth3DS20Information->setBpmpiMerchantUrl($this->configUrl->getValueUrl());
        $auth3DS20Information->setBpmpiTotalAmount($this->priceHelper->currency($quote->getGrandTotal(),false, false));
        $auth3DS20Information->setBpmpiOrderNumber($quote->getEntityId());
        $auth3DS20Information->setBpmpiTransactionMode('S');

        $addressData = $this->auth3DS20GetAddress->getAddressData($quote);
        $auth3DS20Information->setAddressesData($addressData);

        $authCartData = $this->auth3DS20GetCart->getCartData($quote);
        $auth3DS20Information->setCartData($authCartData);

        $userAccount = $this->auth3DS20UserAccount->getUserAccount($quote);
        $auth3DS20Information->setUserAccount($userAccount);

        return $auth3DS20Information;
    }
}
