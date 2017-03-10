<?php

namespace Webjump\BraspagPagador\Model;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface;
use Webjump\BraspagPagador\Api\InstallmentsInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Installment;

class Installments implements InstallmentsInterface
{
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->setBuilder($builder);
    }

    /**
     * @return array
     */
    public function getInstallments()
    {
        $result = [];

        /** @var Installment $item */
        foreach ($this->getBuilder()->build() as $item) {
            $result[] = [
                'id' => $item->getId(),
                'label' => $item->getLabel()
            ];
        }

        return $result;
    }

    protected function setBuilder(BuilderInterface $builder)
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
