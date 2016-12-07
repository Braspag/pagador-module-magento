<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

interface InstallmentsConfigInterface
{
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_IS_ACTIVE = 'payment/%s/installments_active';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_NUMBER = 'payment/%s/installments_number';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_IS_WITH_INTEREST = 'payment/%s/installments_is_with_interest';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_MIN_MOUNT = 'payment/%s/installment_min_amount';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_INTEREST_RATE = 'payment/%s/installments_interest_rate';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_INTEREST_BY_ISSUER = 'payment/%s/installments_interest_by_issuer';
	const CONFIG_XML_BRASPAG_PAGADOR_INSTALLMENTS_MAX_WITHOUT_INTEREST = 'payment/%s/installments_max_without_interest';

	public function isActive();
	
	public function getInstallmentsNumber();

	public function isWithInterest();

	public function getInstallmentMinAmount();

	public function getInterestRate();

	public function isInterestByIssuer();

	public function getInstallmentsMaxWithoutInterest();

}
