<?php

namespace Braspag\BraspagPagador\Helper;

use Magento\Framework\App\Helper\Context;

/**
 * Class Validator
 * @package Braspag\BraspagPagador\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Clean Data;
     *
     * @return string
     */
    public function removeSpecialCharacters($string)
    {
        if ($string === null || $string === '') {
            return (string) $string;
        }
        $string = str_replace(' ', '-', (string) $string);

        $pattern = array("'é'", "'è'", "'ë'", "'ê'", "'É'", "'È'", "'Ë'", "'Ê'", "'á'", "'ã'","'à'", "'ä'", "'â'", "'å'",
            "'Á'", "'Ã'", "'À'", "'Ä'", "'Â'", "'Å'", "'ó'", "'ò'", "'ö'", "'ô'", "'Ó'", "'Ò'", "'Ö'", "'Ô'", "'í'", "'ì'",
            "'ï'", "'î'", "'Í'", "'Ì'", "'Ï'", "'Î'", "'ú'", "'ù'", "'ü'", "'û'", "'Ú'", "'Ù'", "'Ü'", "'Û'", "'ý'",
            "'ÿ'", "'Ý'", "'ø'", "'Ø'", "'œ'", "'Œ'", "'Æ'", "'ç'", "'Ç'");

        $replace = array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'A',
            'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'I', 'u', 'u', 'u', 'u', 'U',
            'U', 'U', 'U', 'y', 'y', 'Y', 'o', 'O', 'a', 'A', 'A', 'c', 'C');

        $string = preg_replace('/[^A-Za-z0-9\-]/', '', preg_replace($pattern, $replace, $string));

        return trim(str_replace('  ', ' ', str_replace('-', ' ', $string)));
    }

    /**
     * @param $taxvat
     * @return null|string|string[]
     */
    public function removeSpecialCharactersFromTaxvat($taxvat)
    {
        if (isset($taxvat)) {
            return preg_replace('/[^A-Za-z0-9]/', '', $taxvat);
        }

        return null;
    }

    /**
     * Determines whether a tax identification number represents a CPF
     * (individual) or CNPJ (legal entity), based on its length.
     *
     * Receita Federal will introduce alphanumeric CNPJs in July/2026, so
     * stripping non-digits before measuring length (the previous approach)
     * would misclassify alphanumeric CNPJs as CPF. Counting the full
     * alphanumeric string length avoids that pitfall: CPF stays at 11 chars
     * and CNPJ at 14, whether numeric or alphanumeric.
     *
     * Input is sanitized internally so callers can safely pass formatted
     * values such as "123.456.789-01" without risk of misclassification.
     *
     * @param string|null $taxvat
     * @return string 'CPF' or 'CNPJ'
     */
    public function getCustomerEntityType($taxvat)
    {
        $sanitized = $this->removeSpecialCharactersFromTaxvat($taxvat);
        return strlen((string) $sanitized) > 11 ? 'CNPJ' : 'CPF';
    }
}