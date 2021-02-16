<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as BoletoConfigInterface;

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
    protected $boletoConfigInterface;

    public function __construct(
        BoletoConfigInterface $boletoConfigInterface
    ) {
        $this->boletoConfigInterface = $boletoConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Boleto Request object should be provided');
        }

        $request = $validationSubject['request'];
        $status = true;
        $message = [];

        return new Result($status, [$message]);
    }
}
