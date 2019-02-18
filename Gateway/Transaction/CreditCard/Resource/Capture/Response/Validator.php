<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Response;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface;

/**
 * Validator
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Validator implements ValidatorInterface
{
    const SUCCESS = 0;

    protected $statusDenied;

    public function validate(array $validationSubject)
    {

        if (!isset($validationSubject['response']) || !$validationSubject['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag Credit Card Capture Response object should be provided');
        }

        $response = $validationSubject['response'];
        $status = true;
        $message = [];

        if ($response->getReasonCode() != self::SUCCESS) {
            $status = false;
            $message = [$response->getProviderReturnMessage()];
        }

        return new Result($status, $message);
    }

}

