<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfigInterface;

/**
 * Validator
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Validator implements ValidatorInterface
{
    protected $creditCardConfigInterface;

    public function __construct(
        CreditCardConfigInterface $creditCardConfigInterface
    ) {
        $this->creditCardConfigInterface = $creditCardConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag CreditCard Authorize Request object should be provided');
        }

        $request = $validationSubject['request'];

        $status = true;
        $message = [];

        if ($this->creditCardConfigInterface->isAuth3Ds20Active()) {

            $failureType = $request->getPaymentExternalAuthenticationFailureType();

            if ($failureType == CreditCardConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$this->creditCardConfigInterface->isAuth3Ds20AuthorizedOnError()
            ) {
                return new Result(false, ["Credit Card Payment Failure. #MPI{$failureType}"]);
            }

            if ($failureType == CreditCardConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$this->creditCardConfigInterface->isAuth3Ds20AuthorizedOnFailure()
            ) {
                return new Result(false, ["Credit Card Payment Failure. #MPI{$failureType}"]);
            }

            if ($failureType == CreditCardConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$this->creditCardConfigInterface->isAuth3Ds20AuthorizeOnUnenrolled()
            ) {
                return new Result(false, ["Credit Card Payment Failure. #MPI{$failureType}"]);
            }

            if ($failureType == CreditCardConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND
                && !$this->creditCardConfigInterface->isAuth3Ds20AuthorizeOnUnsupportedBrand()
            ) {
                return new Result(false, ["Credit Card Payment Failure. #MPI{$failureType}"]);
            }

            if (!$this->creditCardConfigInterface->getIsTestEnvironment()
                && !preg_match("#cielo#is", $request->getPaymentProvider())
                && $failureType != CreditCardConfigInterface::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                return new Result(false, ["Credit Card Payment Failure. #MPI{$failureType}"]);
            }
        }

        return new Result($status, [$message]);
    }
}
