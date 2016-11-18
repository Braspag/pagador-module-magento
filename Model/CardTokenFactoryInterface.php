<?php

namespace Webjump\BraspagPagador\Model;

interface CardTokenFactoryInterface
{
    public function create($alias, $token);
}
