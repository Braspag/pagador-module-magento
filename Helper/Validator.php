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
     * Config path to get word dictionary;
     */
    const XML_PATH_SHOULD_SANITIZE_DICTIONARY = 'address/braspag_pagador_customer_address/address_sanitize_dictionary';

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
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->isSanitizeActive = $context->getScopeConfig()->getValue(self::XML_PATH_SHOULD_SANITIZE,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->sanitizeDictionary = explode(';', $context->getScopeConfig()->getValue(self::XML_PATH_SHOULD_SANITIZE_DICTIONARY,\Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        parent::__construct($context);
    }

    /**
     * @param $district
     * @return array
     */
    public function sanitizeDistrict($district)
    {
        $this->sanitizeDictionary = $this->prepareDictionay();

        if($this->isSanitizeActive && is_string($district))
        {
            preg_match_all("/\w+/", $district, $districtArray);

            $result = [];
            foreach ($districtArray[0] as $word)
            {
                if(array_key_exists($word,$this->sanitizeDictionary)){
                    $result[] = $this->sanitizeDictionary[$word];
                } else {
                    $result[] = $word;
                 }

            }
            return implode(" ",$result);
        }
    }

    /**
     * Prepare Dictionary data;
     *
     * @return bool
     */
    protected function prepareDictionay()
    {
        $data = [];
        foreach ($this->sanitizeDictionary as $item)
        {
            preg_match_all("/\w+/", $item, $keyValue);
            $data[$keyValue[0][0]] = $keyValue[0][1];
        }

        return $data;
    }
}