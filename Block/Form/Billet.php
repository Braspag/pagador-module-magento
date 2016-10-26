<?php

namespace Webjump\BraspagPagador\Block\Form;

use Magento\Payment\Block\Form as PaymentForm;

class Billet extends PaymentForm
{
	protected $_template = 'Webjump_BraspagPagador::form/billet.phtml';

	public function __construct()
	{
		die('teste');
	}
}
