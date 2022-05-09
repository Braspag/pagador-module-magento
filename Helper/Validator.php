<?php

namespace Webjump\BraspagPagador\Helper;

use Magento\Framework\App\Helper\Context;

/**
 * Class Validator
 * @package Webjump\BraspagPagador\Helper
 */
class Validator extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Config path to check if sanitize configuration is active;
     */
    const XML_PATH_SHOULD_SANITIZE = 'address/braspag_pagador_customer_address/address_sanitize';

    /**
     * Config path to get district dictionary;
     */
    const XML_PATH_SANITIZE_DISTRICT_DICTIONARY = 'address/braspag_pagador_customer_address/address_sanitize_district_dictionary';

    /**
     * @var bool|mixed
     */
    protected $isSanitizeActive = true;

    /**
     * @var array
     */
    protected $sanitizeDictionary = [];


    /**
     * Validator constructor.
     *
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->isSanitizeActive = $context->getScopeConfig()->getValue(self::XML_PATH_SHOULD_SANITIZE,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->sanitizeDictionary = explode(';', $context->getScopeConfig()->getValue(self::XML_PATH_SANITIZE_DISTRICT_DICTIONARY,\Magento\Store\Model\ScopeInterface::SCOPE_STORE));


        parent::__construct($context);
    }
    
    /**
     * Sanitize District value based on configuration
     *
     * @param $district
     * @return string | array
     */
    public function sanitizeDistrict($district)
    {
        
        $sanitizeCache = array();
        $sanitizeCache = $this->sanitizeDictionary;

        if($this->isSanitizeActive && is_string($district))
        {
            $sanitizeCache = $this->prepareDictionay();
            preg_match_all("/[^ ]+/", $district, $districtArray);
            $result = [];
            foreach ($districtArray[0] as $word)
            {
                if(array_key_exists($word,$sanitizeCache)){
                    $result[] = $sanitizeCache[$word];
                } else {
                    $result[] = $word;
                 }
            }
            $result = implode(" ", $result);
            $resultLength = strlen($result);

            return ($resultLength > 50 ? substr($result, ($resultLength - 50)) : $result);
        }
        return $district;
    }

    /**
     * Prepare Dictionary data;
     *
     * @return array
     */
    protected function prepareDictionay()
    {
        $data = [];
        foreach ($this->sanitizeDictionary as $item)
        {
            preg_match_all("/[^-]+/", $item, $keyValue);
            $data[$keyValue[0][0]] = $keyValue[0][1];
        }
        return $data;
    }
}