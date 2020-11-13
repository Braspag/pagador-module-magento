<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\TransactionPost\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface;
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

        return new Result($status, [$message]);
    }
}
