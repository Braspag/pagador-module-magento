<?php

namespace Webjump\BraspagPagador\Block\Form;

class CreditCard extends \Magento\Payment\Block\Form\Cc
{
    /**
     * @var string
     */
    protected $_template = 'Webjump_BraspagPagador::form/credit-card.phtml';
}