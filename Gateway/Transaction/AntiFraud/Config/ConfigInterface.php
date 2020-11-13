<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config;


interface ConfigInterface
{
    const COUNTRY_TELEPHONE_CODE = 55;
    const XML_PATH_ACTIVE = 'webjump_braspag_antifraud/general/active';
    const XML_PATH_FINGER_PRINT_ATTRIBUTE= 'webjump_braspag_antifraud/options/sequence';
    const XML_PATH_SEQUENCE = 'webjump_braspag_antifraud/options/sequence';
    const XML_PATH_SEQUENCE_CRITERIA = 'webjump_braspag_antifraud/options/sequence_criteria';
    const XML_PATH_CAPTURE_ON_LOW_RISK = 'webjump_braspag_antifraud/options/capture_in_low_risk';
    const XML_PATH_VOID_ON_HIGH_RISK = 'webjump_braspag_antifraud/options/void_in_high_risk';
    const XML_ORDER_ID_TO_FINGERPRINT   = 'webjump_braspag_antifraud/fingerprint/use_order_id_to_fingerprint';

    public function getSequence();

    public function getSequenceCriteria();

    public function getCaptureOnLowRisk();

    public function getVoidOnHighRisk();

    public function userOrderIdToFingerPrint();
}
