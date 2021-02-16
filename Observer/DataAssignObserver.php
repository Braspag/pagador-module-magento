<?php

namespace Webjump\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Framework\DataObject;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
use Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider as BoletoConfigProvider;

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

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
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

        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        list($provider, $brand) = array_pad(explode('-', $additionalData->getCcType(), 2), 2, null);

        $info->addData([
            'cc_type' => $additionalData->getCcType(),
            'cc_owner' => $additionalData->getCcOwner(),
            'cc_number' => preg_replace('/\D/', '', $additionalData->getCcNumber()),
            'cc_last_4' => substr($additionalData->getCcNumber(), -4),
            'cc_cid' => $additionalData->getCcCid(),
            'cc_exp_month' => $additionalData->getCcExpMonth(),
            'cc_exp_year' => $additionalData->getCcExpYear(),
            'cc_provider' => $provider
        ]);

        if ($brand) {
            $info->setAdditionalInformation('cc_brand', $brand);
        }

        $info->setAdditionalInformation('cc_installments', 1);

        if ($additionalData->getCcInstallments()) {
            $info->setAdditionalInformation('cc_installments', (int) $additionalData->getCcInstallments());
        }

        if ($additionalData->getCcSavecard()) {
            $info->setAdditionalInformation('cc_savecard', (boolean) $additionalData->getCcSavecard());
        }

        if ($cardToken = $this->getCardTokenRepository()->get($additionalData->getCcToken())) {
            $info->setCcType($cardToken->getProvider() . '-' . $cardToken->getBrand());
            $info->setAdditionalInformation('cc_token', $additionalData->getCcToken());
        }

        if ($additionalData->getCcSoptpaymenttoken()) {
            $info->setAdditionalInformation('cc_soptpaymenttoken', $additionalData->getCcSoptpaymenttoken());
        }

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
