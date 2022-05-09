<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\Boleto\Send\Response;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\Validator;

/**
 * Braspag Transaction Boleto Send Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class BaseHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    const ADDITIONAL_INFORMATION_BOLETO_URL = 'boleto_url';
    const ADDITIONAL_INFORMATION_BOLETO_NUMBER = 'boleto_number';
    const ADDITIONAL_INFORMATION_EXPIRATION_DATE = 'expiration_date';
    const ADDITIONAL_INFORMATION_BAR_CODE_NUMBER = 'bar_code_number';
    const ADDITIONAL_INFORMATION_DIGITABLE_LINE = 'digitable_line';

    protected function _handle($payment, $response)
    {
        if (in_array($response->getPaymentStatus(), [
            Validator::NOTFINISHED,
            Validator::DENIED,
            Validator::ABORTED,
            ])) {
            throw new \InvalidArgumentException(__('An error has occurred, please try again later.'));
        }

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_BOLETO_URL, $response->getPaymentUrl());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_BOLETO_NUMBER, $response->getPaymentBoletoNumber());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_EXPIRATION_DATE, $response->getExpirationDate());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_BAR_CODE_NUMBER, $response->getPaymentBarCodeNumber());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_DIGITABLE_LINE, $response->getDigitableLine());

        $payment->unsetData([
            'cc_type',
            'cc_owner',
            'cc_number',
            'cc_cid',
            'cc_exp_month',
            'cc_exp_year',
            'cc_provider',
            'cc_brand'
        ]);

        $payment->unsAdditionalInformation('cc_brand');
        $payment->unsAdditionalInformation('cc_installments');
        $payment->unsAdditionalInformation('cc_savecard');
        $payment->unsAdditionalInformation('cc_token');
        $payment->unsAdditionalInformation('cc_soptpaymenttoken');

        $payment->unsAdditionalInformation('cc_brand');
        $payment->unsAdditionalInformation('send_taxvat');
        $payment->unsAdditionalInformation('send_cardholdername');
        $payment->unsAdditionalInformation('code_financial_table');
        $payment->unsAdditionalInformation('bit120');
        $payment->unsAdditionalInformation('financial_data');

        return $response;
    }
}
