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
namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate;

use Magento\Framework\Session\SessionManagerInterface;
use Webjump\BraspagPagador\Api\OAuth2TokenManagerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\CreateSubordinate\RequestInterface as BraspaglibRequestInterface;

class Request implements BraspaglibRequestInterface
{
    protected $session;

    protected $storeId;

    protected $config;

    protected $httpHost;

    protected $oAuth2TokenManager;

    protected $subordinateId;

    protected $subordinateCorporateName;

    protected $subordinateFancyName;

    protected $subordinateDocumentNumber;

    protected $subordinateDocumentType;

    protected $subordinateMerchantCategoryCode;

    protected $subordinateContactName;

    protected $subordinateContactPhone;

    protected $subordinateMailAddress;

    protected $subordinateWebsite;

    protected $bankAccountBank;

    protected $bankAccountType;

    protected $bankAccountNumber;

    protected $bankAccountOperation;

    protected $bankAccountVerifierDigit;

    protected $bankAccountAgencyNumber;

    protected $bankAccountAgencyDigit;

    protected $bankAccountDocumentNumber;

    protected $bankAccountDocumentType;

    protected $addressStreet;

    protected $addressNumber;

    protected $addressComplement;

    protected $addressNeighborhood;

    protected $addressCity;

    protected $addressState;

    protected $addressZipCode;

    protected $attachmentProofOfBankDomicile;

    protected $attachmentModelOfAdhesionTerm;

    protected $paymentSplitMdrMultipleData;

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
    public function getSubordinateCorporateName()
    {
        return $this->subordinateCorporateName;
    }

    /**
     * @param mixed $subordinateCorporateName
     */
    public function setSubordinateCorporateName($subordinateCorporateName)
    {
        $this->subordinateCorporateName = $subordinateCorporateName;
    }

    /**
     * @return mixed
     */
    public function getSubordinateFancyName()
    {
        return $this->subordinateFancyName;
    }

    /**
     * @param mixed $subordinateFancyName
     */
    public function setSubordinateFancyName($subordinateFancyName)
    {
        $this->subordinateFancyName = $subordinateFancyName;
    }

    /**
     * @return mixed
     */
    public function getSubordinateDocumentNumber()
    {
        return $this->subordinateDocumentNumber;
    }

    /**
     * @param mixed $subordinateDocumentNumber
     */
    public function setSubordinateDocumentNumber($subordinateDocumentNumber)
    {
        $this->subordinateDocumentNumber = $subordinateDocumentNumber;
    }

    /**
     * @return mixed
     */
    public function getSubordinateDocumentType()
    {
        return $this->subordinateDocumentType;
    }

    /**
     * @param mixed $subordinateDocumentType
     */
    public function setSubordinateDocumentType($subordinateDocumentType)
    {
        $this->subordinateDocumentType = $subordinateDocumentType;
    }

    /**
     * @return mixed
     */
    public function getSubordinateMerchantCategoryCode()
    {
        return $this->subordinateMerchantCategoryCode;
    }

    /**
     * @param mixed $subordinateMerchantCategoryCode
     */
    public function setSubordinateMerchantCategoryCode($subordinateMerchantCategoryCode)
    {
        $this->subordinateMerchantCategoryCode = $subordinateMerchantCategoryCode;
    }

    /**
     * @return mixed
     */
    public function getSubordinateContactName()
    {
        return $this->subordinateContactName;
    }

    /**
     * @param mixed $subordinateContactName
     */
    public function setSubordinateContactName($subordinateContactName)
    {
        $this->subordinateContactName = $subordinateContactName;
    }

    /**
     * @return mixed
     */
    public function getSubordinateContactPhone()
    {
        return $this->subordinateContactPhone;
    }

    /**
     * @param mixed $subordinateContactPhone
     */
    public function setSubordinateContactPhone($subordinateContactPhone)
    {
        $this->subordinateContactPhone = $subordinateContactPhone;
    }

    /**
     * @return mixed
     */
    public function getSubordinateMailAddress()
    {
        return $this->subordinateMailAddress;
    }

    /**
     * @param mixed $subordinateMailAddress
     */
    public function setSubordinateMailAddress($subordinateMailAddress)
    {
        $this->subordinateMailAddress = $subordinateMailAddress;
    }

    /**
     * @return mixed
     */
    public function getSubordinateWebsite()
    {
        return $this->subordinateWebsite;
    }

    /**
     * @param mixed $subordinateWebsite
     */
    public function setSubordinateWebsite($subordinateWebsite)
    {
        $this->subordinateWebsite = $subordinateWebsite;
    }

    /**
     * @return mixed
     */
    public function getBankAccountBank()
    {
        return $this->bankAccountBank;
    }

    /**
     * @param mixed $bankAccountBank
     */
    public function setBankAccountBank($bankAccountBank)
    {
        $this->bankAccountBank = $bankAccountBank;
    }

    /**
     * @return mixed
     */
    public function getBankAccountType()
    {
        return $this->bankAccountType;
    }

    /**
     * @param mixed $bankAccountType
     */
    public function setBankAccountType($bankAccountType)
    {
        $this->bankAccountType = $bankAccountType;
    }

    /**
     * @return mixed
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @param mixed $bankAccountNumber
     */
    public function setBankAccountNumber($bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * @return mixed
     */
    public function getBankAccountOperation()
    {
        return $this->bankAccountOperation;
    }

    /**
     * @param mixed $bankAccountOperation
     */
    public function setBankAccountOperation($bankAccountOperation)
    {
        $this->bankAccountOperation = $bankAccountOperation;
    }

    /**
     * @return mixed
     */
    public function getBankAccountVerifierDigit()
    {
        return $this->bankAccountVerifierDigit;
    }

    /**
     * @param mixed $bankAccountVerifierDigit
     */
    public function setBankAccountVerifierDigit($bankAccountVerifierDigit)
    {
        $this->bankAccountVerifierDigit = $bankAccountVerifierDigit;
    }

    /**
     * @return mixed
     */
    public function getBankAccountAgencyNumber()
    {
        return $this->bankAccountAgencyNumber;
    }

    /**
     * @param mixed $bankAccountAgencyNumber
     */
    public function setBankAccountAgencyNumber($bankAccountAgencyNumber)
    {
        $this->bankAccountAgencyNumber = $bankAccountAgencyNumber;
    }

    /**
     * @return mixed
     */
    public function getBankAccountAgencyDigit()
    {
        return $this->bankAccountAgencyDigit;
    }

    /**
     * @param mixed $bankAccountAgencyDigit
     */
    public function setBankAccountAgencyDigit($bankAccountAgencyDigit)
    {
        $this->bankAccountAgencyDigit = $bankAccountAgencyDigit;
    }

    /**
     * @return mixed
     */
    public function getBankAccountDocumentNumber()
    {
        return $this->bankAccountDocumentNumber;
    }

    /**
     * @param mixed $bankAccountDocumentNumber
     */
    public function setBankAccountDocumentNumber($bankAccountDocumentNumber)
    {
        $this->bankAccountDocumentNumber = $bankAccountDocumentNumber;
    }

    /**
     * @return mixed
     */
    public function getBankAccountDocumentType()
    {
        return $this->bankAccountDocumentType;
    }

    /**
     * @param mixed $bankAccountDocumentType
     */
    public function setBankAccountDocumentType($bankAccountDocumentType)
    {
        $this->bankAccountDocumentType = $bankAccountDocumentType;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet()
    {
        return $this->addressStreet;
    }

    /**
     * @param mixed $addressStreet
     */
    public function setAddressStreet($addressStreet)
    {
        $this->addressStreet = $addressStreet;
    }

    /**
     * @return mixed
     */
    public function getAddressNumber()
    {
        return $this->addressNumber;
    }

    /**
     * @param mixed $addressNumber
     */
    public function setAddressNumber($addressNumber)
    {
        $this->addressNumber = $addressNumber;
    }

    /**
     * @return mixed
     */
    public function getAddressComplement()
    {
        return $this->addressComplement;
    }

    /**
     * @param mixed $addressComplement
     */
    public function setAddressComplement($addressComplement)
    {
        $this->addressComplement = $addressComplement;
    }

    /**
     * @return mixed
     */
    public function getAddressNeighborhood()
    {
        return $this->addressNeighborhood;
    }

    /**
     * @param mixed $addressNeighborhood
     */
    public function setAddressNeighborhood($addressNeighborhood)
    {
        $this->addressNeighborhood = $addressNeighborhood;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * @param mixed $addressCity
     */
    public function setAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;
    }

    /**
     * @return mixed
     */
    public function getAddressState()
    {
        return $this->addressState;
    }

    /**
     * @param mixed $addressState
     */
    public function setAddressState($addressState)
    {
        $this->addressState = $addressState;
    }

    /**
     * @return mixed
     */
    public function getAddressZipCode()
    {
        return $this->addressZipCode;
    }

    /**
     * @param mixed $addressZipCode
     */
    public function setAddressZipCode($addressZipCode)
    {
        $this->addressZipCode = $addressZipCode;
    }

    /**
     * @return mixed
     */
    public function getAttachmentProofOfBankDomicile()
    {
        return $this->attachmentProofOfBankDomicile;
    }

    /**
     * @param mixed $attachmentProofOfBankDomicile
     */
    public function setAttachmentProofOfBankDomicile($attachmentProofOfBankDomicile)
    {
        $this->attachmentProofOfBankDomicile = $attachmentProofOfBankDomicile;
    }

    /**
     * @return mixed
     */
    public function getAttachmentModelOfAdhesionTerm()
    {
        return $this->attachmentModelOfAdhesionTerm;
    }

    /**
     * @param mixed $attachmentModelOfAdhesionTerm
     */
    public function setAttachmentModelOfAdhesionTerm($attachmentModelOfAdhesionTerm)
    {
        $this->attachmentModelOfAdhesionTerm = $attachmentModelOfAdhesionTerm;
    }

    public function getPaymentSplitMdrMultipleData()
    {
        return $this->paymentSplitMdrMultipleData;
    }

    /**
     * @param $paymentSplitMdrMultipleData
     * @return mixed|void
     */
    public function setPaymentSplitMdrMultipleData($paymentSplitMdrMultipleData)
    {
        $this->paymentSplitMdrMultipleData = $paymentSplitMdrMultipleData;
    }

    /**
     * @return mixed
     */
    public function getHttpHost()
    {
        return $this->httpHost;
    }

    /**
     * @param mixed $httpHost
     */
    public function setHttpHost($httpHost)
    {
        $this->httpHost = $httpHost;
    }
}
