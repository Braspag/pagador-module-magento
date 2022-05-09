<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\CreateSubordinate\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\CreateSubordinate\RequestInterface;

/**
 * Validator
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Validator implements ValidatorInterface
{

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Request object should be provided');
        }

        $request = $validationSubject['request'];

        $status = true;
        $message = [];

        $validateSubordinateGeneralResult = $this->validateSubordinateGeneralData($request);
        if (!empty($validateSubordinateGeneralResult)) {
            $status = false;
            $message = array_merge($message, $validateSubordinateGeneralResult);
        }

        $validateSubordinateAddressResult = $this->validateSubordinateAddressData($request);
        if (!empty($validateSubordinateAddressResult)) {
            $status = false;
            $message = array_merge($message, $validateSubordinateAddressResult);
        }

        $validateSubordinateAccountBankResult = $this->validateSubordinateAccountBankData($request);
        if (!empty($validateSubordinateAccountBankResult)) {
            $status = false;
            $message = array_merge($message, $validateSubordinateAccountBankResult);
        }

        $validateSubordinateAttachmentsResult = $this->validateSubordinateAttachmentsData($request);
        if (!empty($validateSubordinateAttachmentsResult)) {
            $status = false;
            $message = array_merge($message, $validateSubordinateAttachmentsResult);
        }

        return new Result($status, $message);
    }

    /**
     * @param $request
     * @return array
     */
    public function validateSubordinateGeneralData($request)
    {
        $errorMessages = [];

        $validationFields = [
            'Corporate Name' => [
                    "value" => $request->getSubordinateCorporateName(),
                    "not_empty" => true,
                    "max_length" => 100,
                ],
            'Fancy Name' => [
                "value" => $request->getSubordinateFancyName(),
                "not_empty" => true,
                "max_length" => 50,
            ],
            'Document Number' => [
                "value" => $request->getSubordinateDocumentNumber(),
                "not_empty" => true,
                "max_length" => 14,
            ],
            'Document Type' => [
                "value" => $request->getSubordinateDocumentType(),
                "not_empty" => true,
                "max_length" => 4,
            ],
            'Merchant Category Code' => [
                "value" => $request->getSubordinateMerchantCategoryCode(),
                "not_empty" => true,
                "max_length" => 4,
            ],
            'Contact Name' => [
                "value" => $request->getSubordinateContactName(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Contact Phone' => [
                "value" => $request->getSubordinateContactPhone(),
                "not_empty" => true,
                "max_length" => 11,
            ],
            'Mail Address' => [
                "value" => $request->getSubordinateMailAddress(),
                "not_empty" => true,
                "max_length" => 50,
            ],
            'Website' => [
                "value" => $request->getSubordinateWebsite(),
                "not_empty" => false,
                "max_length" => 200,
            ]
        ];

        $storeGeneralDataValidation = $this->validateFields($validationFields);
        $errorMessages = array_merge($errorMessages, $storeGeneralDataValidation);

        if (!filter_var($request->getSubordinateMailAddress(), FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = __("Invalid '%1'.", __('Mail Address'));
        }

        return $errorMessages;
    }

    /**
     * @param $request
     * @return array
     */
    public function validateSubordinateAddressData($request)
    {
        $errorMessages = [];

        $validationFields = [
            'Address Street' => [
                "value" => $request->getAddressStreet(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Address Number' => [
                "value" => $request->getAddressNumber(),
                "not_empty" => true,
                "max_length" => 15,
            ],
            'Address Complement' => [
                "value" => $request->getAddressComplement(),
                "not_empty" => false,
                "max_length" => 80,
            ],
            'Address Neighborhood' => [
                "value" => $request->getAddressNeighborhood(),
                "not_empty" => true,
                "max_length" => 50,
            ],
            'Address City' => [
                "value" => $request->getAddressCity(),
                "not_empty" => true,
                "max_length" => 50,
            ],
            'Address State' => [
                "value" => $request->getAddressState(),
                "not_empty" => true,
                "max_length" => 2,
            ],
            'Address ZipCode' => [
                "value" => $request->getAddressZipCode(),
                "not_empty" => true,
                "max_length" => 8,
            ],
        ];

        $addressDataValidation = $this->validateFields($validationFields);
        $errorMessages = array_merge($errorMessages, $addressDataValidation);

        return $errorMessages;
    }

    /**
     * @param $request
     * @return array
     */
    public function validateSubordinateAccountBankData($request)
    {
        $errorMessages = [];

        $validationFields = [
            'Bank Code' => [
                "value" => $request->getBankAccountBank(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Type' => [
                "value" => $request->getBankAccountType(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Number' => [
                "value" => $request->getBankAccountNumber(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Operation' => [
                "value" => $request->getBankAccountOperation(),
                "not_empty" => false,
                "max_length" => 100,
            ],
            'Bank Account Verifier Digit' => [
                "value" => $request->getBankAccountVerifierDigit(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Agency Number' => [
                "value" => $request->getBankAccountAgencyNumber(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Agency Digit' => [
                "value" => $request->getBankAccountAgencyDigit(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Owner Document Number' => [
                "value" => $request->getBankAccountDocumentNumber(),
                "not_empty" => true,
                "max_length" => 100,
            ],
            'Bank Account Owner Document Type' => [
                "value" => $request->getBankAccountDocumentType(),
                "not_empty" => true,
                "max_length" => 100,
            ],
        ];

        $bankDataValidation = $this->validateFields($validationFields);
        $errorMessages = array_merge($errorMessages, $bankDataValidation);

        return $errorMessages;
    }

    /**
     * @param $request
     * @return array
     * @TODO attachments data
     */
    public function validateSubordinateAttachmentsData($request)
    {
        $errorMessages= [];
        return $errorMessages;
    }

    /**
     * @param $validationFields
     * @return array
     */
    public function validateFields($validationFields)
    {
        $errorMessages = [];

        $notEmptyValidation = $this->validateFieldsNotEmpty($validationFields);
        $errorMessages = array_merge($errorMessages, $notEmptyValidation);

        $notLengthValidation = $this->validateFieldsLength($validationFields);
        $errorMessages = array_merge($errorMessages, $notLengthValidation);

        return $errorMessages;
    }

    /**
     * @param $notEmptyValidationFields
     * @return array
     */
    public function validateFieldsNotEmpty($notEmptyValidationFields)
    {
        $errorMessages = [];

        foreach ($notEmptyValidationFields as $notEmptyValidationField => $notEmptyValidationFieldValue)
        {
            if (empty($notEmptyValidationFieldValue['value']) && $notEmptyValidationFieldValue['not_empty']) {
                $errorMessages[] = __("The '%1' field can not be empty.", __($notEmptyValidationField));
            }
        }

        return $errorMessages;
    }

    /**
     * @param $notEmptyValidationFields
     * @return array
     */
    public function validateFieldsLength($notEmptyValidationFields)
    {
        $errorMessages = [];

        foreach ($notEmptyValidationFields as $notEmptyValidationField => $notEmptyValidationFieldValue)
        {
            if (strlen($notEmptyValidationFieldValue['value']) > $notEmptyValidationFieldValue['max_length']) {
                $errorMessages[] = __("The length of the '%1' field is longer than allowed. The limit is '%2' characters.", [__($notEmptyValidationField), $notEmptyValidationFieldValue['max_length']]);
            }
        }

        return $errorMessages;
    }
}
