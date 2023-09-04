<?php

declare(strict_types=1);

namespace Braspag\BraspagPagador\Model\Request;

use Braspag\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface as ActionsPaymentRequest;


class CardTwo extends \Magento\Framework\DataObject
{

    protected $requestBuilder;

    protected $twoCardVoidCommand;


    public function __construct(
        BraspagApi $api,
        ActionsPaymentRequest $paymentRequest
    ) {
        $this->paymentRequest = $paymentRequest;
        $this->api = $api;
    }

    public function hasCardTwo()
    {
        return !empty($this->getData());
    }

    public function execute()
    {
        $data = $this->getAdditionalData();

        if (isset($data['cc_amount_card2']) && !empty($data['cc_amount_card2'])) {
            return $this->setData(
                [
                    'cc_cid' => isset($data['cc_cid_card2']) ? $data['cc_cid_card2']: null,
                    'cc_type' => isset($data['cc_type_card2']) ? $data['cc_type_card2']: null,
                    'cc_exp_year' => isset($data['cc_exp_year_card2']) ? $data['cc_exp_year_card2']: null,
                    'cc_exp_month' => isset($data['cc_exp_month_card2']) ? $data['cc_exp_month_card2']: null,
                    'cc_number' => isset($data['cc_number_card2']) ? $data['cc_number_card2']: null,
                    'cc_last_4' => isset($data['cc_number_card2']) ?substr($data['cc_number_card2'], -4): null,
                    'cc_owner' =>isset($data['cc_owner_card2']) ? $data['cc_owner_card2']: null,
                    'cc_installments' => isset($data['cc_installments_card2']) ? $data['cc_installments_card2']: null,
                    'total_amount' => $data['cc_amount_card2'],
                    'taxvat_card2' => isset($data['cc_taxvat_card2']) ? $data['cc_taxvat_card2']: null,
                    'taxvat_card' => isset($data['cc_taxvat']) ? $data['cc_taxvat']: null,
                    'transactionId' => isset($data['two_card_payment_id']) ? $data['two_card_payment_id']: null,
                    'cc_token' => isset($data['card_cc_token_card2']) ? $data['card_cc_token_card2']: null,
                    'cc_alias' => isset($data['cc_alias_card2']) ? $data['cc_alias_card2']: null,
                    'cc_installments_text' => isset($data['cc_installments_text_card2']) ? $data['cc_installments_text_card2']: null,
                ]
            )->toArray();
        }

        $this->unsAdditionalData();
    }

    /**
     * @return string
     */
    public function getPaymentData()
    {
        return $this->getData();
    }

    public function build($paymentId)
    {

        try {

            $this->getPaymentRequest()->setPaymentId($paymentId);

            /**
             * @var Braspag\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response $paymentInfo
             */

            return $this->getApi()->voidPayment($this->getPaymentRequest());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Magento\Sales\Exception\CouldNotRefundException(
                __('Braspag communication error. Error code: ' . $e->getCode())
            );
        }
    }

    /**
     * @return \Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface
     */
    public function getPaymentRequest()
    {
        return $this->paymentRequest;
    }


    public function getApi()
    {
        return $this->api;
    }
}
