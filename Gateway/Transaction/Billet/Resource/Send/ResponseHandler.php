<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\ResponseInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\Validator;

/**
 * Braspag Transaction Billet Send Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class ResponseHandler implements HandlerInterface
{
    const ADDITIONAL_INFORMATION_BILLET_URL = 'billet_url';
    const ADDITIONAL_INFORMATION_BOLETO_NUMBER = 'boleto_number';
    const ADDITIONAL_INFORMATION_EXPIRATION_DATE = 'expiration_date';
    const ADDITIONAL_INFORMATION_BAR_CODE_NUMBER = 'bar_code_number';
    const ADDITIONAL_INFORMATION_DIGITABLE_LINE = 'digitable_line';

    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        if (!isset($response['response']) || !$response['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag Billet Send Response Lib object should be provided');
        }

        /** @var ResponseInterface $response */
        $response = $response['response'];


        if (in_array($response->getPaymentStatus(), [
            Validator::NOTFINISHED,
            Validator::DENIED,
            Validator::ABORTED,
            ])) {
            throw new \InvalidArgumentException(__('An error has occurred, please try again later.'));
        }

        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_BILLET_URL, $response->getPaymentUrl());
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

        return $this;
    }
}
