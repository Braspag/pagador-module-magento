<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfigInterface;

/**
 * Validator
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Validator implements ValidatorInterface
{
	const NOTFINISHED = 0;
	const AUTHORIZED = 1;
	const PAYMENTCONFIRMED = 2;
	const DENIED = 3;
	const VOIDED = 10;
	const REFUNDED = 11;
	const PENDING = 12;
	const ABORTED = 13;
	const SCHEDULED = 20;

    protected $statusDenied;
    protected $creditCardConfigInterface;

    public function __construct(
        CreditCardConfigInterface $creditCardConfigInterface
    ) {
        $this->creditCardConfigInterface = $creditCardConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response']) || !$validationSubject['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag Credit Card Authorize Response object should be provided');
        }

        $response = $validationSubject['response'];
        $status = true;
        $message = [];

        if (in_array($response->getPaymentStatus(), $this->getStatusDenied($response))) {
            $status = false;

            $message = $response->getPaymentProviderReturnMessage();
            if (empty($message)) {
                $message = "Credit Card Payment Failure. #BP{$response->getPaymentStatus()}";
            }
        }

        return new Result($status, [$message]);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function getStatusDenied(ResponseInterface $response)
    {
        if (! $this->statusDenied) {

            $this->statusDenied = [self::DENIED, self::VOIDED, self::ABORTED];

            if (! $response->getAuthenticationUrl()) {
                $this->statusDenied[] = self::NOTFINISHED;
            }
        }

        return $this->statusDenied;
    }
}
