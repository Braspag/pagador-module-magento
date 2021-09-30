<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate\RequestFactory;

/**
 * Braspag Transaction Boleto Send Request Builder
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

/**
 * Class RequestBuilder
 * @package Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate
 */
class RequestBuilder implements BuilderInterface
{
    protected $requestFactory;
    protected $config;
    protected $requestHttp;

    /**
     * RequestBuilder constructor.
     * @param \Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate\RequestFactory $requestFactory
     * @param ConfigInterface $config
     * @param \Magento\Framework\App\Request\Http $requestHttp
     */
    public function __construct(
        RequestFactory $requestFactory,
        ConfigInterface $config,
        \Magento\Framework\App\RequestInterface $requestHttp
    ) {
        $this->setRequestFactory($requestFactory);
        $this->setConfig($config);
        $this->setRequestHttp($requestHttp);
    }

    /**
     * @param array $buildSubject
     * @return array|mixed
     * @throws \Exception
     */
    public function build(array $buildSubject)
    {

        if (!isset($buildSubject['data']) || !is_array($buildSubject['data'])) {

            throw new \InvalidArgumentException(__('Subordinate data should be provided'));
        }

        if (!isset($buildSubject['subordinate'])) {
            throw new \InvalidArgumentException(__('Subordinate Id should be provided'));
        }

        $request = $this->getRequestFactory()->create();

        $request->setConfig($this->getConfig());

        $data = $buildSubject['data'];
        $subordinateId = $buildSubject['subordinate'];

        $request->setSubordinateId($subordinateId);
        $request->setHttpHost($this->getRequestHttp()->getHttpHost());

        $this->buildSubordinateGeneralData($data, $request);
        $this->buildSubordinateAddressData($data, $request);
        $this->buildSubordinateAccountBankData($data, $request);
        $this->buildSubordinateAttachmentsData($data, $request);
        $this->buildMdrMultipleData($data, $request);

        return $request;
    }

    /**
     * @return mixed
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param mixed $requestFactory
     */
    public function setRequestFactory($requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param ConfigInterface $config
     * @return $this
     */
    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getRequestHttp()
    {
        return $this->requestHttp;
    }

    /**
     * @param mixed $requestHttp
     */
    public function setRequestHttp($requestHttp)
    {
        $this->requestHttp = $requestHttp;
    }

    /**
     * @param $data
     * @param $request
     * @return $this
     */
    public function buildSubordinateGeneralData($data, $request)
    {
        $request->setSubordinateCorporateName($data['subordinate_general_corporate_name']);
        $request->setSubordinateFancyName($data['subordinate_general_fancy_name']);
        $request->setSubordinateDocumentNumber($data['subordinate_general_document_number']);
        $request->setSubordinateDocumentType($data['subordinate_general_document_type']);
        $request->setSubordinateMerchantCategoryCode($data['subordinate_general_merchant_category_code']);
        $request->setSubordinateContactName($data['subordinate_general_contact_name']);
        $request->setSubordinateContactPhone($data['subordinate_general_contact_phone']);
        $request->setSubordinateMailAddress($data['subordinate_general_mail_address']);
        $request->setSubordinateWebsite($data['subordinate_general_website']);

        return $request;
    }

    /**
     * @param $data
     * @param $request
     * @return mixed
     */
    public function buildSubordinateAddressData($data, $request)
    {
        $request->setAddressStreet($data['subordinate_address_street']);
        $request->setAddressNumber($data['subordinate_address_number']);
        $request->setAddressComplement($data['subordinate_address_complement']);
        $request->setAddressNeighborhood($data['subordinate_address_neighborhood']);
        $request->setAddressCity($data['subordinate_address_city']);
        $request->setAddressState($data['subordinate_address_state']);
        $request->setAddressZipCode($data['subordinate_address_zipcode']);

        return $request;
    }

    /**
     * @param $data
     * @param $request
     * @return mixed
     */
    public function buildSubordinateAccountBankData($data, $request)
    {
        $request->setBankAccountBank($data['subordinate_bank_account_bank_code']);
        $request->setBankAccountType($data['subordinate_bank_account_type']);
        $request->setBankAccountNumber($data['subordinate_bank_account_number']);
        $request->setBankAccountOperation($data['subordinate_bank_account_operation']);
        $request->setBankAccountVerifierDigit($data['subordinate_bank_account_verifier_digit']);
        $request->setBankAccountAgencyNumber($data['subordinate_bank_account_agency_number']);
        $request->setBankAccountAgencyDigit($data['subordinate_bank_account_agency_digit']);
        $request->setBankAccountDocumentNumber($data['subordinate_bank_account_document_number']);
        $request->setBankAccountDocumentType($data['subordinate_bank_account_document_type']);

        return $request;
    }

    /**
     * @param $data
     * @param $request
     * @return mixed
     */
    public function buildSubordinateAttachmentsData($data, $request)
    {
//        $request->setAttachmentProofOfBankDomicile($data['subordinate_general_corporate_namecorporate_name']);
//        $request->setAttachmentModelOfAdhesionTerm($data['subordinate_general_corporate_namecorporate_name']);

        return $request;
    }

    /**
     * @param $data
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function buildMdrMultipleData($data, $request)
    {
        $mdrsFromConfig = $this->getConfig()->getPaymentSplitMarketPlaceGeneralPaymentSplitMdrMultiple();

        $mdrsFromData = explode("\r\n", $mdrsFromConfig);

        $mdrMultiplePattern = "^Product-(.*?)\|Brand-(.*?)\|InitialInstallmentNumber-(.*?)\|FinalInstallmentNumber-(.*?)\|Percent-(.*?)$";

        $mdrResultData = [];
        foreach ($mdrsFromData as $mdrFromData) {

            if (empty(trim($mdrFromData))) {
                continue;
            }

            if (!preg_match_all("#{$mdrMultiplePattern}#is", $mdrFromData, $match)) {
                throw new \Exception("Invalid MRD config Data");
            }

            $mdrResultData[] = [
                "PaymentArrangement" => [
                    "Product" => $match[1][0],
                    "Brand" => $match[2][0]
                ],
                "InitialInstallmentNumber" => $match[3][0],
                "FinalInstallmentNumber" => $match[4][0],
                "Percent" => $match[5][0]
            ];

            $request->setPaymentSplitMdrMultipleData($mdrResultData);
        }

        return $request;
    }
}
