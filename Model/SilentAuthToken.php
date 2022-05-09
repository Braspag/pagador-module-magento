<?php

namespace Webjump\BraspagPagador\Model;


use Webjump\BraspagPagador\Api\SilentAuthTokenInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost\BuilderInterface;

class SilentAuthToken implements SilentAuthTokenInterface
{
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->setBuilder($builder);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getBuilder()->build();
    }

    protected function setBuilder($builder)
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return BuilderInterface
     */
    protected function getBuilder()
    {
        return $this->builder;
    }

}
