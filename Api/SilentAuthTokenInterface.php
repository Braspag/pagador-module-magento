<?php

namespace Webjump\BraspagPagador\Api;


interface SilentAuthTokenInterface
{
    /**
     * @return string
     */
    public function getToken();
}