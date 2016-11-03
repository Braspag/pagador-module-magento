<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

interface InstallmentsConfigInterface
{
	public function getInstallmentsNumber();

	public function isWithInterest();

	public function getInstallmentMinAmount();

	public function getInterestRate();

	public function isInterestByIssuer();

	public function getinstallmentsMaxWithoutInterest();

}
