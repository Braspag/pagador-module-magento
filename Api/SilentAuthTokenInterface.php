<?php

namespace Braspag\BraspagPagador\Api;

interface SilentAuthTokenInterface
{
    /**
     * @return string
     */
    public function getToken();
}