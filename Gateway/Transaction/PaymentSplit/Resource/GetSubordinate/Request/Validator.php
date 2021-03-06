<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\PaymentSplit\Resource\GetSubordinate\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Braspag\Braspag\Pagador\Transaction\Api\PaymentSplit\GetSubordinate\RequestInterface;

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

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Request object should be provided');
        }

        $status = true;
        $message = [];

        return new Result($status, [$message]);
    }
}