<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Pix\Resource\Send\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Braspag\Braspag\Pagador\Transaction\Api\Pix\Send\ResponseInterface;
use Braspag\Braspag\Pagador\Transaction\Resource\Pix\Send\Response;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;
use Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\Validator;

/**
 * Braspag Transaction Pix Send Response Handler
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

    const ADDITIONAL_INFORMATION_PIX_REASONMESSAGE = 'pix_reasonmessage';
    const ADDITIONAL_INFORMATION_PIX_REASONCODE = 'pix_reasoncode';
    const ADDITIONAL_INFORMATION_PIX_QRCODEEXPIRATION = 'QrCodeExpiration';
    const ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE = 'QrcodeBase64Image';
    const ADDITIONAL_INFORMATION_PIX_QRCODESTRING = 'QrCodeString';
    const ADDITIONAL_INFORMATION_PIX_PROVIDERRETURNMESSAGE = 'ProviderReturnMessage';
    const ADDITIONAL_INFORMATION_PIX_TRANSACTIONID = 'pix_transaction_id';

    protected function _handle($payment, $response)
    {
        if (
            in_array($response->getPayment()['Status'], [
            Validator::NOTFINISHED,
            Validator::DENIED,
            Validator::ABORTED,
            ])
        ) {
            throw new \InvalidArgumentException(__('An error has occurred, please try again later.'));
        }

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_REASONMESSAGE, $response->getPaymentReasonMessage());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_REASONCODE, $response->getPaymentReasonCode());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_QRCODEEXPIRATION, $response->getPaymentExpirationDate());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE, $response->getPaymentQrCodeBase64Image());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_QRCODESTRING, $response->getPaymentQrCodeString());
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_TRANSACTIONID, $response->getPaymentPaymentId());

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