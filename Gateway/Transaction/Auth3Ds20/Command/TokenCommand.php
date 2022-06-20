<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command;

use Braspag\BraspagPagador\Api\Auth3Ds20TokenCommandInterface;
use Braspag\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Braspag\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface;

/**
 * Class TokenCommand
 * @package Braspag\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command
 */
class TokenCommand implements Auth3Ds20TokenCommandInterface
{
    protected $api;

    public function __construct(BraspagApi $api)
    {
        $this->api = $api;
    }

    public function execute(RequestInterface $request)
    {
        return $this->api->getToken($request);
    }
}