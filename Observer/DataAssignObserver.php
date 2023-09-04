<?php

namespace Braspag\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Framework\DataObject;
use Braspag\BraspagPagador\Api\CardTokenRepositoryInterface;
use Braspag\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider as BoletoConfigProvider;
use Braspag\BraspagPagador\Model\Payment\Transaction\Pix\Ui\ConfigProvider as PixConfigProvider;
use Braspag\BraspagPagador\Model\Request\CardTwo;

/**
 * Credit Card Data Assign
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    protected $cardTokenRepository;
    
    protected $cardTwo;

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository,
        CardTwo $cardTwo
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
        $this->cardTwo = $cardTwo;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $info = $method->getInfoInstance();

        $info->unsetData([
            'cc_type',
            'cc_owner',
            'cc_number',
            'cc_last_4',
            'cc_cid',
            'cc_exp_month',
            'cc_exp_year',
            'cc_provider'
        ]);

        $info->unsAdditionalInformation('cc_brand');
        foreach ($info->getAdditionalInformation() as $key => $value) {
            $info->unsAdditionalInformation($key);
        }

        if ($info->getMethodInstance()->getCode() === BoletoConfigProvider::CODE) {
            return $this;
        }

        if ($info->getMethodInstance()->getCode() === PixConfigProvider::CODE) {
            return $this;
        }

        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        $ccType = $additionalData->getCcType();
        $ccNumber = $additionalData->getCcNumber();
        $ccToken = $additionalData->getCcToken();

       if (isset($ccNumber) && isset($ccType)) {

            list($provider, $brand) = array_pad(explode('-', $ccType, 2), 2, null);

            $info->addData([
                'cc_type' => $ccType,
                'cc_owner' => $additionalData->getCcOwner(),
                'cc_number' => preg_replace('/\D/', '', $ccNumber),
                'cc_last_4' => substr($ccNumber, -4),
                'cc_cid' => $additionalData->getCcCid(),
                'cc_exp_month' => $additionalData->getCcExpMonth(),
                'cc_exp_year' => $additionalData->getCcExpYear(),
                'cc_provider' => $provider
            ]);
    
            if ($brand) {
                $info->setAdditionalInformation('cc_brand', $brand);
            }

         }
         

        $info->setAdditionalInformation('cc_installments', 1);

        if ($additionalData->getCcInstallments()) {
            $info->setAdditionalInformation('cc_installments', (int) $additionalData->getCcInstallments());
        }

        if ($additionalData->getCcSavecard()) {
            $info->setAdditionalInformation('cc_savecard', (bool) $additionalData->getCcSavecard());
        }

        if ($cardToken = $this->getCardTokenRepository()->get($additionalData->getCcToken())) {
            $info->setCcType($cardToken->getProvider() . '-' . $cardToken->getBrand());
            $info->setAdditionalInformation('cc_token', $additionalData->getCcToken());
            $info->setAdditionalInformation('cc_alias', $cardToken->getAlias());
        }

        if ($cardTokenTwo = $this->getCardTokenRepository()->get($additionalData->getData('card_cc_token_card2'))) {
            $additionalData->setData('cc_alias_card2', $cardTokenTwo->getAlias());
        }

        if ($additionalData->getCcSoptpaymenttoken()) {
            $info->setAdditionalInformation('cc_soptpaymenttoken', $additionalData->getCcSoptpaymenttoken());
        }

        if ($additionalData->getCcTaxvat()) {
            $info->setAdditionalInformation('cc_taxvat', $additionalData->getCcTaxvat());
        }

        if ($additionalData->getCcInstallmentsText()) {
            $info->setAdditionalInformation('cc_installments_text', $additionalData->getCcInstallmentsText());
        }

        if ($additionalData->getCcOwner()) {
            $info->setAdditionalInformation('cc_owner', $additionalData->getCcOwner());
        }

        $this->cardTwo->setAdditionalData($additionalData)->execute();
        
        $this->processExtraData($additionalData, $info);

        return $this;
    }

    /**
     * @param $additionalData
     * @param $info
     * @return $this
     */
    protected function processExtraData($additionalData, $info)
    {
        $info->setAdditionalInformation('authentication_failure_type', $additionalData->getAuthenticationFailureType());

        if (!empty($additionalData->getAuthenticationCavv())) {
            $info->setAdditionalInformation('authentication_cavv', $additionalData->getAuthenticationCavv());
        }

        if (!empty($additionalData->getAuthenticationXid())) {
            $info->setAdditionalInformation('authentication_xid', $additionalData->getAuthenticationXid());
        }

        if (!empty($additionalData->getAuthenticationEci())) {
            $info->setAdditionalInformation('authentication_eci', $additionalData->getAuthenticationEci());
        }

        if (!empty($additionalData->getAuthenticationVersion())) {
            $info->setAdditionalInformation('authentication_version', $additionalData->getAuthenticationVersion());
        }

        if (!empty($additionalData->getAuthenticationReferenceId())) {
            $info->setAdditionalInformation('authentication_reference_id', $additionalData->getAuthenticationReferenceId());
        }

        return $this;
    }

    protected function getCardTokenRepository()
    {
        return $this->cardTokenRepository;
    }

    protected function setCardTokenRepository($cardTokenRepository)
    {
        $this->cardTokenRepository = $cardTokenRepository;

        return $this;
    }
}