<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\OAuth2\Command;

use Webjump\BraspagPagador\Api\OAuth2TokenCommandInterface;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Webjump\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface;

/**
 * Class TokenCommand
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command
 */
class TokenCommand implements OAuth2TokenCommandInterface
{
    protected $api;

    public function __construct(BraspagApi $api)
    {
        $this->api = $api;
    }

    public function execute(RequestInterface $request)
    {
        return $this->api->getOAuth2Token($request);
    }
}