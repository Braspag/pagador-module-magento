<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Void\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface;

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
    const SUCCESS = 0;

    protected $statusDenied;

    public function validate(array $validationSubject)
    {

        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Transaction Void Request object should be provided');
        }

        return new Result(true, []);
    }

}

