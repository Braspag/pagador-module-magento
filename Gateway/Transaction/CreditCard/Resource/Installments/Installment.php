<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments;

class Installment implements InstallmentInterface
{
    protected $id;

    protected $label;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }
}
