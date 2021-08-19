<?php

namespace Webjump\BraspagPagador\Helper;

use Magento\Framework\App\Helper\Context;

/**
 * Class Validator
 * @package Webjump\BraspagPagador\Helper
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
        $string = str_replace(' ', '-', $string);

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
        return preg_replace('/[^A-Za-z0-9]/', '', $taxvat);
    }
}